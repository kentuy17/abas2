<h2>Statement of Account Aging Report</h2>

<a href="<?php echo HTTP_PATH.'statements_of_account/filter_SOA_aging_report'; ?>" data-toggle="modal" data-target="#modalDialog" data-backdrop="static"> <button type="button" class="btn btn-success exclude-pageload">Filter</button></a>
<a href="<?php echo HTTP_PATH."statements_of_account/SOA_aging_report"; ?>"><button type="button" class="btn btn-dark force-pageload">Refresh</button></a>
<a href="<?php echo HTTP_PATH."statements_of_account/print_SOA_aging_report?date_from=".$date_from."&date_to=".$date_to."&company_id=".$company_id."&client_id=".$client_id; ?>" target="_blank"><button type="button" class="btn btn-info exclude-pageload">Print</button></a>



	<table data-toggle="table" id="statements-of-account-table" data-show-columns="true" data-search="true" data-cache="false" data-pagination="true" data-page-list="[5,10,20,50,100,1000,10000]" data-export-data-type="all" data-show-export="true" data-export-types="['excel']" data-show-footer="false" data-export-data-type="all" data-search='false' data-show-export="true" data-export-types="['excel']">

	<thead>
		<tr>
			<th data-field="id" data-align="center" data-visible="false" data-sortable="true">Transaction Code</th>
			<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" >SOA No.</th>
			<th data-field="contract" data-align="center" data-visible="false" data-sortable="true" >Contract</th>
			<th data-field="reference_number" data-align="center" data-visible="true" data-sortable="true" >Reference No.</th>
			<th data-field="company_name" data-align="left" data-visible="true" data-sortable="false" >Company</th>
			<th data-field="client_name" data-align="left" data-visible="true"  data-sortable="false">Client</th>
			<th data-field="services" data-align="left" data-visible="true" data-sortable="true" >Services</th>
			<th data-field="date" data-align="left" data-visible="true" data-sortable="true" >Date Received by Client</th>
			<th data-field="due" data-align="left" data-visible="true" data-sortable="true" >Due Date</th>
			<th data-field="aging" data-align="left" data-visible="true" data-sortable="false" >Aging</th>
			<th data-field="type" data-align="left" data-visible="false" data-sortable="true" data-filter-control="select">Type</th>
			<th data-field="total_amount" data-align="right" data-visible="true" data-sortable="false" >SOA Amount</th>
			<th data-field="current" data-align="right" data-visible="true" data-sortable="false" >Current</th>
			<th data-field="one_to_thirty_days" data-align="right" data-visible="true" data-sortable="false" >1 to 30 Days</th>
			<th data-field="thirty_one_to_sixty_days" data-align="right" data-visible="true" data-sortable="false" >31 to 60 Days</th>
			<th data-field="sixty_one_to_one_hundred_twenty_days" data-align="right" data-visible="true" data-sortable="false" >61 to 120 Days</th>
			<th data-field="over_one_hundred_twenty_days" data-align="right" data-visible="true" data-sortable="false" >Over 120 Days</th>
			<th data-field="total_aging_amount" data-align="right" data-visible="true" data-sortable="false" >Total</th>
			<th data-field="status" data-align="left" data-visible="false" data-sortable="true" data-filter-control="select">Status</th>

		</tr>
	</thead>

	<tbody>
		<?php
			if(isset($rows)){
				foreach ($rows as $row) {
					echo "<tr>";
						echo "<td>".$row['id']."</td>";
						echo "<td>".$row['control_number']."</td>";
						echo "<td>".$row['contract']."</td>";
						echo "<td>".$row['reference_number']."</td>";
						echo "<td>".$row['company_name']."</td>";
						echo "<td>".$row['client_name']."</td>";
						echo "<td>".$row['services']."</td>";
						echo "<td>".$row['date']."</td>";
						echo "<td>".$row['due']."</td>";
						echo "<td>".$row['aging']."</td>";
						echo "<td>".$row['type']."</td>";
						echo "<td>".$row['total_amount']."</td>";
						echo "<td>".$row['current']."</td>";
						echo "<td>".$row['one_to_thirty_days']."</td>";
						echo "<td>".$row['thirty_one_to_sixty_days']."</td>";
						echo "<td>".$row['sixty_one_to_one_hundred_twenty_days']."</td>";
						echo "<td>".$row['over_one_hundred_twenty_days']."</td>";
						echo "<td>".$row['total_aging_amount']."</td>";
						echo "<td>".$row['status']."</td>";
					echo "</tr>";
				}
			}
		?>
	</tbody>
</table>


<script type="javascript/text">
	$(function () {
		var $table = $('#statements-of-account-table');
		$table.bootstrapTable();
	});
</script>