<h2>Company Inventory</h2>
<?php 
	$company_options = "<option value=''>All Companies</option>";
	if(!empty($companies)) {
		foreach($companies as $option) {
			$company_options	.=	"<option ".($option->id==$company ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
		}
		unset($option);
	}
	$loc_options = "<option value=''>All Locations</option>";
	if(!empty($locations)) {
		foreach($locations as $loc) {
			$loc_options	.=	"<option ".($loc->location_name==$location ? "selected":"")." value='".$loc->location_name."'>".$loc->location_name."</option>";
		}
		unset($option);
	}
	if(!isset($company)){
		$data_url = HTTP_PATH."inventory/items/load";
	}else{
		if(isset($location)){
			$data_url = HTTP_PATH."inventory/items/load/".$company."/".$location;
		}else{
			$data_url = HTTP_PATH."inventory/items/load/".$company;
		}
	}
?>
<div class='panel panel-body'><label>You are viewing the inventory of company:</label><select id="company" name="company" class="form-control"><?php echo $company_options;?></select><label>Location:</label><select id="location" name="location" class="form-control"><?php echo $loc_options;?></select></div>
<?php if($this->Abas->checkPermissions("inventory|add_item",false)): ?>
	<a href="<?php echo HTTP_PATH ?>inventory/items/add" data-toggle="modal" class="btn btn-success btn" data-target="#modalDialog" class='exclude-pageload' data-backdrop="static">Add</a>
<?php endif; ?>

<?php if($this->Abas->checkPermissions("inventory|unit_conversion",false)): ?>
	<!--<a href="<?php //echo HTTP_PATH ?>inventory/items/add_conversion" data-toggle="modal" data-target="#modalDialogNorm" class="btn btn-warning btn" class='exclude-pageload' data-backdrop="static"> UOM Conversion</a>-->
<?php endif; ?>
<a href="<?php echo HTTP_PATH."inventory/items/listview"; ?>" class="btn btn-dark force-pageload">Refresh</a>
<span class='pull-right'>
<a href="<?php echo HTTP_PATH ?>inventory/qr_code_scanner" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static" class="btn btn-default btn"><i class="glyphicon glyphicon-qrcode"></i> QR Code Scanner</a>
</span>
<br><br><br>
<table data-toggle="table" id="inventory-table" class="table table-bordered table-striped table-hover" data-url="<?php echo $data_url; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]"  data-sort-name="description" data-sort-order="asc"  data-search="true" data-strict-search="false" data-filter-control='true' data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
	<thead>
		<tr>
			<?php if($company){ ?>
				<th data-field="company_name" data-align="center" data-visible='false' data-sortable="true" data-filter-control="input">Company</th>
			<?php }else{ ?>
				<th data-field="company_name" data-align="center" data-visible='true' data-sortable="true" data-filter-control="input">Company</th>
			<?php } ?>
			<th data-field="item_id" data-align="center" data-visible='false' data-sortable="true" data-filter-control="input">Item ID</th>
			<th data-field="item_code" data-align="center" data-sortable="true" data-filter-control="input">Item Code</th>
			<th data-field="description" data-align="left" data-sortable="true" data-filter-control="input">Item Name</th>
			<th data-field="brand" data-align="left" data-sortable="true" data-filter-control="input">Brand</th>
			<th data-field="particular" data-visible="true" data-align="center" data-sortable="true" data-filter-control="input">Particular</th>
			<th data-field="category_name" data-visible="true" data-align="center" data-sortable="false" data-filter-control="select">Category</th>
			<th data-field="type" data-align="left" data-sortable="true" data-visible="false" data-filter-control="input">Type</th>
			<?php if($location){ ?>
				<th data-field="location" data-align="left" data-sortable="true" data-visible="false" data-filter-control="input">Location</th>
			<?php }else{ ?>
				<th data-field="location" data-align="left" data-sortable="true" data-visible="true" data-filter-control="input">Location</th>
			<?php } ?>	
			<th data-field="quantity" data-visible="true" data-align="center" data-sortable="true" data-filter-control="select"> Total Quantity on Stock</th>
			<th data-field="unit" data-visible="true" data-align="center" data-sortable="true" data-filter-control="select">UOM</th>
			<th data-field="created_by" data-visible="false" data-align="center" data-sortable="true" data-filter-control="select">Created By</th>
			<th data-field="created_on" data-visible="false" data-align="center" data-sortable="true" data-filter-control="select">Created On</th>
			<th data-field="status" data-visible="false" data-align="center" data-sortable="true" data-filter-control="select">Status</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
		</tr>
	</thead>
</table>
<script>
	var company_selected = $('#company').val();
	if(company_selected !=''){
		$('#location').prop('disabled',false);
	}else{
		$('#location').prop('disabled',true);
	}
	$('#company').change(function(e) {
		var company_id = $(this).val();
		window.location.replace("<?php echo HTTP_PATH."inventory/items/listview/"; ?>"+company_id);
		if(company_id !=''){
			$('#location').prop('disabled',false);
		}else{
			$('#location').prop('disabled',true);
		}
	});

	$('#location').change(function(e) {
		var company_id = $('#company').val();
		var location = $('#location').val();
        window.location.replace("<?php echo HTTP_PATH."inventory/items/listview/"; ?>"+company_id+"/"+location);  
	});

	function operateFormatter(value, row, index) {
		return [
		    	'<a class="btn btn-warning btn-xs btn-block btn-xs" href="<?php echo HTTP_PATH.'inventory/items/edit/'; ?>'+row['id']+"/"+row['company_id']+"/"+row['location']+'" data-toggle="modal" data-target="#modalDialog">View/Edit</a>'
			<?php if($this->Abas->checkPermissions("inventory|purchase_report",false)): ?>
			,'<a class="btn btn-dark btn-xs btn-block btn-xs" href="<?php echo HTTP_PATH.'inventory/purchase_report/filter/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialogNorm">Purchase History</a> '
			<?php endif; ?>
		].join('');
	}
	$(function () {
		var $table = $('#inventory-table');
		$table.bootstrapTable();
	});
</script>
