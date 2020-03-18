<?php if($this->Abas->checkPermissions("inventory|add_item",false)): ?>
	<a href="<?php echo HTTP_PATH ?>inventory/item_form" data-toggle="modal" class="btn btn-primary btn" data-target="#modalDialogNorm" class='exclude-pageload' data-backdrop="static"><i class="glyphicon glyphicon-plus"></i> Add Item</a>
<?php endif; ?>
<?php if($this->Abas->checkPermissions("inventory|add_receiving",false)): ?>
	<a href="<?php echo HTTP_PATH ?>inventory/receiving_form" data-toggle="modal" data-target="#modalDialog" class="btn btn-success btn" class='exclude-pageload' data-backdrop="static"><i class="glyphicon glyphicon-import"></i> Receiving</a>
<?php endif; ?>
<?php if($this->Abas->checkPermissions("inventory|add_issuance",false)): ?>
<a href="<?php echo HTTP_PATH ?>inventory/issuance_form" data-toggle="modal" data-target="#modalDialog" class="btn btn-danger btn" class='exclude-pageload' data-backdrop="static"><i class="glyphicon glyphicon-export"></i> Issuance</a>
<?php endif; ?>
<?php if($this->Abas->checkPermissions("inventory|add_transfer",false)): ?>
	<a href="<?php echo HTTP_PATH ?>inventory/transfer_form" data-toggle="modal" data-target="#modalDialog" class="btn btn-info btn" class='exclude-pageload' data-backdrop="static"><i class="glyphicon glyphicon-resize-full"></i> Transfer</a>
<?php endif; ?>
<?php if($this->Abas->checkPermissions("inventory|add_return",false)): ?>
	<a href="<?php echo HTTP_PATH ?>inventory/return_form" data-toggle="modal" data-target="#modalDialog" class="btn btn-dark btn" class='exclude-pageload' data-backdrop="static"><i class="glyphicon glyphicon-download-alt"></i> Return</a>
<?php endif; ?>
<?php if($this->Abas->checkPermissions("inventory|unit_conversion",false)): ?>
	<a href="<?php echo HTTP_PATH ?>inventory/item_unit_conversion/form" data-toggle="modal" data-target="#modalDialogNorm" class="btn btn-warning btn" class='exclude-pageload' data-backdrop="static"><i class="glyphicon glyphicon-share"></i> Item Unit Conversion</a>
<?php endif; ?>
<a href="<?php echo HTTP_PATH ?>inventory/qr_code_reader_tool" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static" class="btn btn-default btn"><i class="glyphicon glyphicon-qrcode"></i> QR Code Reader</a>
<table data-toggle="table" id="inventory-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."inventory/view_all_items"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
	<thead>
		<tr>
			<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input">Item ID</th>
			<th data-field="item_code" data-align="center" data-sortable="true" data-filter-control="input">Item Code</th>
			<th data-field="description" data-align="left" data-sortable="true" data-filter-control="input">Item Name</th>
			<th data-field="particular" data-visible="true" data-align="center" data-sortable="true" data-filter-control="input">Particular</th>
			<th data-field="category_name" data-visible="true" data-align="center" data-sortable="false" data-filter-control="input">Category</th>
			<th data-field="unit_price" data-visible="false" data-align="center" data-sortable="true" data-filter-control="input">Unit Price</th>
			<th data-field="tayud_quantity" data-visible="true" data-align="center" data-sortable="false" data-filter-control="input">Tayud Quantity</th>
			<th data-field="nra_quantity" data-visible="true" data-align="center" data-sortable="true" data-filter-control="input">NRA Quantity</th>
			<th data-field="makati_quantity" data-visible="true" data-align="center" data-sortable="true" data-filter-control="select">Makati Quantity</th>
			<th data-field="unit" data-visible="true" data-align="center" data-sortable="true" data-filter-control="select">Unit</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
		</tr>
	</thead>
</table>
<script>
	function operateFormatter(value, row, index) {
		return [
			'<a class="btn btn-warning btn-xs btn-block btn-xs" href="<?php echo HTTP_PATH.'inventory/item_form/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Edit</a> '
			<?php if($this->Abas->checkPermissions("inventory|purchase_report",false)): ?>
			,'<a class="btn btn-dark btn-xs btn-block btn-xs" href="<?php echo HTTP_PATH.'inventory/purchase_report/filter/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Purchase Report</a> '
			<?php endif; ?>
		].join('');
	}
	$(function () {
		var $table = $('#inventory-table');
		$table.bootstrapTable();
	});
</script>
