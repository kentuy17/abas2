<h2 id="glyphicons-glyphs">Materials & Supplies Return Slips</h2>

<?php if($this->Abas->checkPermissions("inventory|add_return",false)): ?>
<a href="<?php echo HTTP_PATH ?>inventory/return/add" data-toggle="modal" data-target="#modalDialog" title="Return">
    <button type="button" class="btn btn-success btn-m">Add</button>
</a>
<?php endif ?>

<a href="<?php echo HTTP_PATH ?>inventory/return/listview" class="btn btn-dark force-pageload">Refresh</a>

		<div>
         	<div>		
				<table data-toggle="table" id="data-table_return" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'inventory/return/load';?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="true" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
					<thead>
						<tr>
							<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Transaction Code</th>
							<th data-field="return_date" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">Return Date</th>
							<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">MSRS No.</th>
							<th data-field="company_name" data-align="center" data-visible="true" data-sortable="false" data-filter-control="select">Company</th>
							<th data-field="return_from" data-align="center" data-visible="true" data-sortable="false" data-filter-control="select">Returned From</th>
							<th data-field="return_to" data-align="center" data-visible="true" data-sortable="false" data-filter-control="select">Returned To</th>
							<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Created On</th>
							<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Created By</th>
							<th data-field="operate" data-formatter="viewMSRS" data-events="operateEvents"  data-align="center" data-align="center">Manage</th>
						</tr>
					</thead>
				</table>
         	</div>
		</div>
	</div>
<script>
	$(function () {
		var $table2 = $('#data-table_return');
		$table2.bootstrapTable();
	});

	function viewMSRS(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block" href="<?php echo HTTP_PATH.'inventory/return/view/';?>'+row['id']+'" data-backdrop="static">View</a>',
		].join('');
	}
</script>