<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		Filter: Out-turn Summary Aging Report
	</div>
</div>
<div class="modal-body">
	<form action="<?php echo HTTP_PATH .'operation/out_turn_summary_aging_report/result'?>" method="POST" id="filter_form">
				<div class="col-xs-12 col-sm-6">
				<label for="date_from">From:* </label>
				<input class="form-control input-sm" type="date" name="date_from" id="date_from" value="date_from" required/>
				</div>
				<div class="col-xs-12 col-sm-6">
				<label for="date_to">To:* </label>
				<input class="form-control input-sm" type="date" name="date_to" id="date_to" value="date_to" required/>
				</div>
		</div>
		<div class='modal-footer'>
			<input class="btn btn-danger pull-right" value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:20px">
			<input class="btn btn-success pull-right" type="submit" value="Filter" id="submitbtn" name="submitbtn"  style="width:100px; margin-left:30px; margin-top:20px;" onclick='javascript:submitForm()'>
		</div>
	</form>

<script type="text/javascript">

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