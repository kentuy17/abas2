<h2 id="glyphicons-glyphs">Material & Supplies Issuance Slips</h2>

<?php if($this->Abas->checkPermissions("inventory|add_issuance",false)): ?>
<a href="<?php echo HTTP_PATH ?>inventory/issuance/add" data-toggle="modal" data-target="#modalDialog" title="Issuance">
    <button type="button" class="btn btn-success btn-m">Add</button>
</a>
<?php endif ?>

<a href="<?php echo HTTP_PATH ?>inventory/issuance/listview" class="btn btn-dark force-pageload">Refresh</a>

		<div>
         	<div>		
				<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'inventory/issuance/load';?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="true" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
					<thead>
						<tr>
							<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Transaction Code</th>

							<th data-field="issue_date" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">Issuance Date</th>

							<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">MSIS No.</th>

							<th data-field="company_name" data-align="center" data-visible="true" data-sortable="false" data-filter-control="select">Company</th>

							<th data-field="issued_to" data-align="center" data-visible="true" data-filter-control="input" data-sortable="false">Issued To</th>
							
							<th data-field="issued_for" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select">Received By</th>

							<th data-field="total_amount" data-align="center" data-visible="true" data-sortable="false" data-filter-control="input">Total Amount</th>

							<th data-field="from_location" data-align="center" data-visible="true" data-sortable="false" data-filter-control="select">Location</th>

							<th data-field="gatepass" data-align="center" data-visible="false" data-sortable="false" data-filter-control="select">Gate-pass?</th>

							<th data-field="is_cleared" data-align="center" data-visible="false" data-sortable="false" data-filter-control="select">Acctg Cleared?</th>

							<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Created On</th>

							<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Created By</th>	

							<th data-field="operate" data-formatter="viewRR" data-events="operateEvents"  data-align="center" data-align="center">Manage</th>
						</tr>
					</thead>
				</table>
         	</div>
		</div>
	</div>
<script>

	$(function () {
		var $table1 = $('#data-table');
		$table1.bootstrapTable();
	});

	function viewRR(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block" href="<?php echo HTTP_PATH.'inventory/issuance/view/';?>'+row['id']+'" data-backdrop="static">View</a>',
		].join('');
	}

</script>