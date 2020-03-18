<?php
$form_action= HTTP_PATH."mastertables/inventory_items/insert";
$title="Add New Item";
$e = array(
	"item_code"=>"",
	"description"=>"",
	"particular"=>"",
	"unit"=>"",
	"unit_price"=>"",
	"reorder_level"=>"",
	"discontinued"=>"",
	"sub_category"=>"",
	"stat"=>"",
	"qty"=>"",
	"category"=>"",
	"location"=>"",
	"stock_location"=>"",
	"account_type"=>"",
	"requested"=>""
);
if (isset($existing)){
	$form_action= HTTP_PATH."mastertables/inventory_items/update".$existing['id'];
	$title="Edit Item";
	$e=$existing;
	}
?>
<div class="panel panel-primary">
	<div class= "panel-heading" height= "min-height">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class "modal title"><?php echo $title; ?></h5>
	</div>
	<div class="panel-body">
		<form action = "<?php echo $form_action; ?>" method='POST' role='form'>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for= 'item_code'>Item Code</label>
				<input type='text' class='form-control' id='item_code' name='item_code' value='<?php echo $e['item_code']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='description'>Description</label>
				<input type='text' class='form-control' id='description' name='description' value='<?php echo $e['description']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='particular'>Particular</label>
				<input type='text' class='form-control' id='particular' name='particular' value='<?php echo $e['particular']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='unit'>Unit</label>
				<input type='text' class='form-control' id='unit' name='unit' value='<?php echo $e['unit']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='unit_price'>Unit Price</label>
				<input type='text' class='form-control' id='unit_price' name='unit_price' value='<?php echo $e['unit_price']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='reorder_level'>Reorder Level</label>
				<input type='text' class='form-control' id='reorder_level' name='reorder_level' value='<?php echo $e['reorder_level']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='discontinued'>Discontinued</label>
				<input type='text' class='form-control' id='discontinued' name='discontinued' value='<?php echo $e['discontinued']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='sub_category'>Sub Category</label>
				<input type='text' class='form-control' id='sub_category' name='sub_category' value='<?php echo $e['sub_category']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='stat'>Stat</label>
				<input type='text' class='form-control' id='stat' name='stat' value='<?php echo $e['stat']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='qty'>Quantity</label>
				<input type='text' class='form-control' id='qty' name='qty' value='<?php echo $e['qty']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='category'>Category</label>
				<input type='text' class='form-control' id='category' name='category' value='<?php echo $e['category']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='location'>Location</label>
				<input type='text' class='form-control' id='location' name='location' value='<?php echo $e['location']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='stock_location'>Stock Location</label>
				<input type='text' class='form-control' id='stock_location' name='stock_location' value='<?php echo $e['stock_location']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='account_type'>Account Type</label>
				<input type='text' class='form-control' id='account_type' name='account_type' value='<?php echo $e['account_type']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='requested'>Requested</label>
				<input type='text' class='form-control' id='requested' name='requested' value='<?php echo $e['requested']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<input type='submit' class='btn btn-primary' name='save' value='save'/>
			</div>
		</form>
	</div>
</div>