<div class="xx_title">
	<h2>Accounting Entries Summary Report</h2>
	<h5><?php echo "For Company: ".$company->name;?></h5>
	<h5><?php echo "Dates from ". date('F j, Y',strtotime($from_date))." to ".date('F j, Y',strtotime($to_date));?></h5>
	<h5><?php echo "Status: ".$status;?></h5>
</div>
<a id="filterDate" href="<?php  echo HTTP_PATH.'accounting/accounting_entries_summary_report/filter'; ?>" class="glyphicon-th-user btn btn-success pull-center exclude-pageload" data-toggle="modal" data-target="#modalDialogNorm" title="Filter">Filter</a>

	<table data-toggle="table" id="summary" data-show-columns="true" data-search="true" data-cache="false" data-pagination="true" data-filter-control="false" data-filter-strict-search="false" data-page-list="[5,10,20,50,100,1000,10000]" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">

		<thead>
			<tr>
				<th data-sortable='true' data-align='center'>#</th>
				<th data-filter-control='input' data-sortable='true' data-align='center' data-visible='false'>Transaction Code No.</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Company</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Vessel</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Contract</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Department</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Account Code</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Account Name</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Particulars</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Remarks</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'  data-visible='false'>Transaction ID</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'  data-visible='false'>Reference Table</th>
				<th data-filter-control='input' data-sortable='true' data-align='center' data-visible='false'>Reference ID</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Debit</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Credit</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Posted On</th>
				<th data-filter-control='input' data-sortable='true' data-align='center'>Posted By</th>
			</tr>
		</thead>

		<tbody>
			<?php

				$ctr=1;
				if(isset($result)){
					foreach($result as $row){
						echo "<tr>";
							echo "<td>".$ctr."</td>";
							echo "<td>".$row->id."</td>";
							echo "<td>".$row->company_name."</td>";
							echo "<td>".$row->vessel_name."</td>";
							echo "<td>".$row->contract_name."</td>";
							echo "<td>".$row->department_name."</td>";
							echo "<td>".$row->account_code."</td>";
							echo "<td>".$row->account_name."</td>";
							echo "<td>".$row->particular."</td>";
							echo "<td>".$row->remark."</td>";
							echo "<td>".$row->transaction_id."</td>";
							echo "<td>".$row->reference_table."</td>";
							echo "<td>".$row->reference_id."</td>";
							echo "<td>".number_format($row->debit_amount,2,".",",")."</td>";
							echo "<td>".number_format($row->credit_amount,2,".",",")."</td>";
							echo "<td>".$row->posted_on."</td>";
							echo "<td>".$row->posted_by_name."</td>";
						echo "</tr>";
						$ctr++;
					}
				}
			?>
		</tbody>
	</table>		   