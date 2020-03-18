<h2>UOM Conversion History</h2><br>
<h4>Item Name: <?php echo $item[0]['description']." - ".$item[0]['unit']; ?></h4>
<p>Converted from <?php echo date("j F Y", strtotime($dstart)); ?> to <?php echo date("j F Y", strtotime($dfinish)); ?></p>
<table class="table table-bordered table-striped table-hover" data-search="true">
		<tr>
			<th rowspan="2">#</th>
			<th rowspan="2">Company</th>
			<th rowspan="2">Location</th>
			<th colspan="3">From</th>
			<th colspan="3">To</th>
			<th rowspan="2">Converted On</th>
			<th rowspan="2">Converted By</th>
		</tr>
		<tr>
			<th>Qty</th>
			<th>Unit</th>
			<th>Price</th>
			<th>Qty</th>
			<th>Unit</th>
			<th>Price</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$ctr=1;
			if($history){
				foreach($history as $row){
					echo "<tr>";
						echo "<td>".$ctr."</td>";
						$company = $this->Abas->getCompany($row->company_id);
						echo "<td>".$company->name."</td>";
						echo "<td>".$row->location."</td>";
						echo "<td>".number_format($row->from_quantity,0,'.',',')."</td>";
						echo "<td>".$row->from_unit."</td>";
						echo "<td>".number_format($row->from_price,0,'.',',')."</td>";
						echo "<td>".number_format($row->to_quantity,0,'.',',')."</td>";
						echo "<td>".$row->to_unit."</td>";
						echo "<td>".number_format($row->to_price,2,'.',',')."</td>";
						echo "<td>".date('Y-m-d',strtotime($row->created_on))."</td>";
						$user = $this->Abas->getUser($row->created_by);
						echo "<td>".$user['full_name']."</td>";
					echo "</tr>";
					$ctr++;
				}
			}else{
				echo "<tr><td colspan='11'><center>No record found.</center></td></tr>";
			}
		?>
	</tbody>
</table>
