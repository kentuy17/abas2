<table data-toggle="table" id="data-table_po" class="table table-bordered table-striped table-hover"data-pagination="true" data-show-columns="true" data-page-list="[100, 200, 500, 1000, 2500, 5000]" data-page-size="10" data-search="true" data-filter-control="true" data-filter-strict-search="false">
	<thead>
		<tr>
			<th data-field="supplier_name" data-align="center" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Payee</th>
			<th data-field="count" data-align="center" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">No. of APV</th>
			<th data-field="details" data-align="center">Details</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($apv_processing as $row){
				echo "<tr>";
					$supplier = $this->Abas->getSupplier($row->supplier_id);
					echo "<td>".$supplier['name']."</td>";
					echo "<td>".$row->apv_count."</td>";
					echo "<td><a class='btn btn-info btn-xs btn-block force-pageload' href='".HTTP_PATH."accounting/check_voucher/add/".$row->supplier_id."/PO' data-toggle='modal' data-target='#modalDialog' data-backdrop='static'>Process</a></td>";
				echo "</tr>";
			}
		?>
	</tbody>
</table>
<script type="text/javascript">
	$(function () {
		var $table = $('#data-table_po');
		$table.bootstrapTable();
	});
</script>