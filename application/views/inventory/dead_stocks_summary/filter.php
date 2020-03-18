<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		Filter: Dead Stocks Summary
	</div>
</div>		

	<div class="modal-body">
		<form action="<?php echo HTTP_PATH .'inventory/dead_stocks_summary/result'?>" method="POST" id="filter_form">
		<div class="col-xs-12 col-sm-6">
			<label for="items">From:* </label>
			<input class="form-control input-sm" type="date" name="start_date" id="start_date" required/>
		</div>
		<div class="col-xs-12 col-sm-6">
			<label for="items">To:* </label>
			<input class="form-control input-sm" type="date" name="end_date" id="end_date" required/>
		</div>
	</div>
	<div class='modal-footer'>
		<input class="btn btn-danger pull-right" value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:20px">
		<input class="btn btn-success pull-right" type="submit" value="Filter" id="submitbtn" name="submitbtn"  style="width:100px; margin-left:30px; margin-top:20px;" onclick='javascript:submitForm()'>
	</div>
</form>

<script type="text/javascript">
function submitForm() {
	var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    if(start_date=='' || end_date==''){
			toastr["warning"]("Please fill-out all required fields(*).<br/>","ABAS Says");
        	return false;
    }
	document.getElementById("filter_form").submit();
	return true;
}
</script>