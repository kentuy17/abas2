<h2>Monthly Inventory Report</h2>
<?php if($this->Abas->checkPermissions("inventory|add_monthly_inventory_report",false)): ?>
	<a href="<?php echo HTTP_PATH ?>inventory/monthly_inventory_report/add" data-toggle="modal" class="btn btn-success btn" data-target="#modalDialogNorm" class='exclude-pageload'>Add</a>
<?php endif; ?>
<a href="<?php echo HTTP_PATH."inventory/monthly_inventory_report/listview"; ?>" class="btn btn-dark force-pageload">Refresh</a>
<table data-toggle="table" id="monthly-inventory-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'inventory/monthly_inventory_report/load'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]"  data-sort-name="id" data-sort-order="desc"  data-search="true" data-strict-search="false" data-filter-control='true'>

	<thead>
		<tr>
			<th data-field="id" data-align="center" data-visible='false' data-sortable="true" data-filter-control="input">Transaction Code</th>
			<th data-field="company_name" data-align="center" data-visible='true' data-sortable="true" data-filter-control="select">Company</th>
			<th data-field="month_of" data-align="center" data-visible='true' data-sortable="true" data-filter-control="input">For the month of</th>
			<th data-field="location" data-align="center" data-visible='true' data-sortable="true" data-filter-control="select">Location</th>
			<th data-field="created_on" data-align="center" data-visible='false' data-sortable="true" data-filter-control="input">Prepared On</th>
			<th data-field="created_by" data-align="center" data-sortable="true" data-visible="true" data-filter-control="input">Prepared By</th>
			<th data-field="status" data-visible="true" data-align="center" data-sortable="true" data-filter-control="select">Status</th>
			<th data-field="operateFormatter" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
		</tr>
	</thead>
</table>
<script>
	function operateFormatter(value, row, index) {
		return [
		    	'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'inventory/monthly_inventory_report/print/'; ?>'+ row['id'] +'" target="_blank">Print</a>',
		    	'<a class="btn btn-danger btn-xs btn-block" onclick="javascript:voidReport('+ row['id'] +');">Void</a>'
		].join('');
	}
	$(function () {
		var $table = $('#monthly-inventory-table');
		$table.bootstrapTable();
	});
	function voidReport(id){
		bootbox.confirm({
			title: "Void Monthly Inventory Report",
			size: 'small',
		    message: "Are you sure you want to void this Monthly Inventory Report?",
		    buttons: {
		        confirm: {
		            label: 'Yes',
		            className: 'btn-success'
		        },
		        cancel: {
		            label: 'No',
		            className: 'btn-danger'
		        }
		    },
		    callback: function (result) {
		    	if(result){
			        window.location.href = "<?php echo HTTP_PATH.'inventory/monthly_inventory_report/void/';?>" + id;
		    	}
		    }
		});
	}
</script>
