<div class="xx_title">
		<h2>Acknowledgement Receipts Summary Report</h2>
	</div>
		<a id="filterDate" href="<?php echo '../filter_summary_report/acknowledgement_receipts'; ?>" class="glyphicon-th-user btn btn-success pull-center exclude-pageload" data-toggle="modal" data-target="#modalDialog" title="Filter">Filter</a>
		<a id="reset" href="<?php echo HTTP_PATH."accounting/summary_report/acknowledgement_receipts"; ?>" class="glyphicon-th-user btn btn-dark pull-center force-pageload" title="Refresh">Refresh</a>

		<a id="print" href="<?php echo '../print_summary_report/acknowledgement_receipts/?date_from='. $date_from . '&date_to=' . $date_to .'&company='. $company?>" class="glyphicon-th-user btn btn-info pull-center exclude-pageload" title="Print" target="_blank">Print</a>

		<table data-toggle="table" id="summary" data-show-columns="true" data-search="true" data-cache="false" data-pagination="true" data-filter-control="false" data-filter-strict-search="false" data-page-list="[5,10,20,50,100,1000,10000]" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">

			<thead>
				<tr>
					<th data-sortable='true' data-align='center'>#</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>AR No.</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Company</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Payor</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Particulars</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Mode of Collection</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Issued On</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Issued By</th>
					<th data-filter-control='input' data-sortable='true' data-align='right'>Amount</th>
				</tr>
			</thead>

			<tbody>
				<?php

					$ctr=1;
					if(isset($receipts)){
						foreach($receipts as $row){
							echo "<tr>";
								echo "<td>".$ctr."</td>";
								echo "<td>".$row->ar_num."</td>";
								echo "<td>".$row->company."</td>";
								echo "<td>".$row->payor."</td>";
								echo "<td>".$row->particulars."</td>";
								echo "<td>".$row->mode_of_collection."</td>";
								echo "<td>".date('Y-M-d',strtotime($row->issued_date))."</td>";
								echo "<td>".$row->issued_by."</td>";
								echo "<td>".number_format($row->net_amount,2,".",",")."</td>";
							echo "</tr>";
							$ctr++;
						}
					}
				?>
			</tbody>
		</table>		   