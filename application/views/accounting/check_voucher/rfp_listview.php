<table data-toggle="table" id="data-table_non_po" class="table table-bordered table-striped table-hover" data-pagination="true" data-show-columns="true" data-page-list="[100, 200, 500, 1000, 2500, 5000]" data-page-size="10" data-search="true" data-filter-control="true" data-filter-strict-search="false">
	<thead>
		<tr>
			<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
			<th data-field="control_number" data-align="center" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
			<th data-field="company_name" data-align="center"  data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Company</th>
			<th data-field="request_date" data-align="center" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Request Date</th>
			<th data-field="supplier_name" data-align="center" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Payee</th>
			<th data-field="payee_type" data-align="center" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Payee Type</th>
			<th data-field="amount" data-align="center" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Amount</th>
			<th data-field="details" data-align="center">Details</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($rfp_processing as $row){
				echo "<tr>";
					echo "<td>".$row['id']."</td>";
					echo "<td>".$row['control_number']."</td>";
					$company = $this->Abas->getCompany($row['company_id']);
					echo "<td>".$company->name."</td>";
					echo "<td>".date('F j, Y',strtotime($row['request_date']))."</td>";
					if($row['payee_type']=='Supplier'){
						$supplier = $this->Abas->getSupplier($row['payee']);
						$payee_name = $supplier['name'];
					}else{
						$employee = $this->Abas->getEmployee($row['payee']);
						$payee_name = $employee['full_name'];
					}
					if($row['payee']==''){
						$payee_name	= $row['payee_others'];
					}
					echo "<td>".$payee_name."</td>";
					echo "<td>".$row['payee_type']."</td>";
					echo "<td>".number_format($row['amount'],2,'.',',')."</td>";
					echo "<td><a class='btn btn-info btn-xs btn-block force-pageload' href='".HTTP_PATH."accounting/check_voucher/add/".$row['id']."/Non-PO' data-toggle='modal' data-target='#modalDialog' data-backdrop='static'>Process</a></td>";
				echo "</tr>";
			}
		?>
	</tbody>
</table>
<script type="text/javascript">
	$(function () {
		var $table = $('#data-table_non_po');
		$table.bootstrapTable();
	});
</script>