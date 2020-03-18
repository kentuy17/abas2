<a href='<?php echo HTTP_PATH; ?>mastertables/inventory_items/add' class='btn btn-primary' role = 'button' data-toggle='modal' data-target='#modalDialog'>Add</a>
<table data-toggle="table" id="inventory_items-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH; ?>mastertables/inventory_items/json" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-search="true" data-page-list ="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" >
<thead>
	<tr>
		<th data-sortable= "true" data-align= "center" data-field= 'item_code'>Item Code</th>
		<th data-sortable= "true" data-align= "center" data-field= 'description'>Description</th>
		<th data-sortable= "true" data-align= "center" data-field= 'particular'>Particular</th>
		<th data-sortable= "true" data-align= "center" data-field= 'unit'>Unit</th>
		<th data-sortable= "true" data-align= "center" data-field= 'unit_price'>Unit Price</th>
		<th data-sortable= "true" data-align= "center" data-field= 'reorder_level'>Reorder Level</th>
		<th data-sortable= "true" data-align= "center" data-field= 'discontinued'>Discontinued</th>
		<th data-sortable= "true" data-align= "center" data-field= 'sub_category'>Sub Category</th>
		<th data-sortable= "true" data-align= "center" data-field= 'stat'>Stat</th>
		<th data-sortable= "true" data-align= "center" data-field= 'qty'>Quantity</th>
		<th data-sortable= "true" data-align= "center" data-field= 'category'>Category</th>
		<th data-sortable= "true" data-align= "center" data-field= 'location'>Location</th>
		<th data-sortable= "true" data-align= "center" data-field= 'stock_location'>Stock Location</th>
		<th data-sortable= "true" data-align= "center" data-field= 'account_type'>Account Type</th>
		<th data-sortable= "true" data-align= "center" data-field= 'requested'>Requested</th>
	</tr>
</thead>
</table>