<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		Filter: Stock Card
	</div>
</div>		

	<div class="modal-body">
		<form action="<?php echo HTTP_PATH .'inventory/stock_card/result'?>" method="POST" id="filter_form">
		<div class="col-xs-12 col-sm-12">
			<label for="items">Item Name:* </label>
			<input class="form-control input-sm" type="text" name="item_name" id="item_name" required/>
			<input class="form-control input-sm" type="hidden" name="item_id" id="item_id" required/>
		</div>
	</div>
	<div class='modal-footer'>
		<input class="btn btn-danger pull-right" value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:20px">
		<input class="btn btn-success pull-right" type="submit" value="Filter" id="submitbtn" name="submitbtn"  style="width:100px; margin-left:30px; margin-top:20px;" onclick='javascript:submitForm()'>
	</div>
</form>

<script type="text/javascript">

$( "#item_name" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>inventory/item_data/true",
	minLength: 2,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		toastr.clear();
	},
	select: function( event, ui ) {
		$( "#item_name" ).val( ui.item.label );
		$( "#item_id" ).val( ui.item.value );
		return false;
	}
});

function submitForm() {
	var all_inputs = $('#filter_form :visible').find('input').filter('[required]');
	for(var x = 0; x < all_inputs.length; x++){
    	if (all_inputs[x].value==""){
        	toastr["warning"]("Please fill-out all required fields(*).<br/>","ABAS Says");
        	return false;
        }
    }
	document.getElementById("filter_form").submit();
	return true;
}
</script>