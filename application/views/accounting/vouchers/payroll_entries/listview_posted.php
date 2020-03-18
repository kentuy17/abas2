<h2 id="glyphicons-glyphs">Payroll Entries - Posted</h2><br>

		
	<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/payroll_entries/load/posted';?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="payroll_date" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500,1000,2000,3000,5000,10000]" data-search="true" data-filter-control='true'>
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Transaction Code</th>

				<th data-field="payroll_coverage" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input">Payroll Period</th>

				<th data-field="payroll_date" data-align="center" data-visible="true" data-sortable="false" data-filter-control="input">Payroll Date</th>

				<th data-field="company_name" data-align="center" data-visible="true" data-sortable="false" data-filter-control="select">Company</th>

				<th data-field="status" data-align="center" data-visible="true" data-filter-control="select" data-sortable="false">Status</th>

				<th data-field="operate" data-formatter="viewPayrollEntries" data-events="operateEvents"  data-align="center" data-align="center">Manage</th>
			</tr>
		</thead>
	</table>

<script src="<?php echo LINK ?>assets/gentelella-master/vendors/bootbox/bootbox.min.js"></script>

<script>

	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

	function viewPayrollEntries(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block" href="<?php echo HTTP_PATH.'accounting/payroll_entries/view/posted/';?>'+row['payroll_id']+'/'+row['id']+'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">View</a> ',
		].join('');
	}
	
</script>