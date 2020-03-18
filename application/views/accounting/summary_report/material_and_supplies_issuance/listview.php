<?php

		$table_header = null;
		$table_data = null;
		$company = null;
		$vessel = null;
		$ctr = 1;
		$grandtotal = 0;

		if(isset($type)){

			if($type=="MSIS" || $type=="MSIS_consolidated" ){

				$table_header = "<thead>
								<tr>
									<th data-filter-control='input' data-sortable='true' data-sortable='true' data-align='center' data-field='id'>Transaction Code</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='issue_date'>Issuance Date</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='company'>Company</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='control_number'>MSIS No.</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='issued_to'>Issued To</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='vessel_id'>Issued For</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='location'>Location</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='item_id'>Item</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='qty'>Quantity</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='unit' >Unit</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='unit_price' data-footer-formatter='totalTextFormatter'>Unit Price</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' total-field='amount' data-footer-formatter='sumFormatter'>Amount</th>
								</tr>
								</thead>";

				if(isset($summary)){
					foreach($summary as $record){

						$vessel_name = "";
						$company_name = "";
						$item_name = "";
						$amount = "";

						$vessel = $this->Abas->getVessel($record['vessel_id']);
							if(!empty($vessel->name)){
								$vessel_name = $vessel->name;
							}else{
								$vessel_name="-";
							}

						if(!empty($vessel->name)){
							$company = $this->Abas->getCompany($vessel->company);
							if(!empty($company->name)){
								$company_name = $company->name;
							}else{
								$company_name = "-";
							}
						}else{
							$company_name = "-";
						}
						
						$item = $this->Inventory_model->getItem($record['item_id']);
							if(!empty($item[0]['description'])){
								if($item[0]['item_code']!=0){
									$item_name = $item[0]['item_code'] . "-" . $item[0]['description'];
								}
								else{
									$item_name = $item[0]['description'];
								}
							}else{
								$item_name="-";
							}

						$amount = ($record['unit_price'] * $record['qty']);

						if($type=='MSIS_consolidated'){
							$company_name = $this->Abas->getCompany($record['company_id'])->name;
						}

						$table_data .= "<tr>";
						$table_data .= "<td>" . $record['id'] . "</td>";
						$table_data .= "<td>" . $record['issue_date'] . "</td>";
						$table_data .= "<td>" . $company_name . "</td>";
						$table_data .= "<td>" . $record['control_number'] . "</td>";
						$table_data .= "<td>" . $record['issued_to'] . "</td>";
						$table_data .= "<td>" . $vessel_name . "</td>";
						$table_data .= "<td>" . $record['from_location'] . "</td>";
						$table_data .= "<td>" . $item_name . "</td>";
						$table_data .= "<td>" . $record['qty'] . "</td>";
						$table_data .= "<td>" . $record['unit'] . "</td>";
						$table_data .= "<td>" . $record['unit_price'] . "</td>";
						$table_data .= "<td>" . number_format($amount,2,".",",")  . "</td>";
						$table_data .= "</tr>";

						$grandtotal = $grandtotal + $amount;

						
					}
					

				}

			}

		}

	?>

		<h2>Material and Supplies Issuances Summary Report</h2>

		<a id="filterDate" href="<?php echo '../filter_summary_report/' . $type; ?>" class="glyphicon-th-user btn btn-success pull-center exclude-pageload" data-toggle="modal" data-target="#modalDialog" title="Filter">Filter</a>
		<a id="reset" href="<?php echo HTTP_PATH."accounting/summary_report/" . $type; ?>" class="glyphicon-th-user btn btn-dark pull-center force-pageload" title="Refresh">Refresh</a>
		<a id="print" href="<?php echo '../print_summary_report/'. $type . '?location=' . $location . '&filter=' . $filter .'&date_from=' . $date_from . '&date_to=' . $date_to; ?>" class="glyphicon-th-user btn btn-info pull-center exclude-pageload" title="Print" target="_blank">Print</a>
	

		<table data-toggle="table" id="summary" data-show-columns="true" data-search="true" data-cache="false" data-pagination="true" data-filter-control="true" data-filter-strict-search="true" data-page-list="[5,10,20,50,100,1000,10000]" data-export-data-type="all" data-show-export="true" data-export-types="['excel']" data-show-footer="false">

			<?php echo $table_header; ?>

			<tbody>
				<?php echo $table_data; ?>
			</tbody>

		</table>

<script type="text/javascript">
	
$(function () {
    $('#summary').bootstrapTable({
        showExport: true,
        exportOptions: {
            fileName: 'Summary of Report - MSIS'
        }
    });
});

function totalTextFormatter(data) {
    return 'Total Amount:';
}

function sumFormatter(data) {
    var field = this.field;
    var total_sum = data.reduce(function(sum, row) {
        return (sum) + (parseInt(row[field]) || 0);
    }, 0);
    return total_sum.toLocaleString('en-US',{ minimumFractionDigits: 2 });
}

</script>