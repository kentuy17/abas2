<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		Filter: Stock Level
	</div>
</div>		
	<div class="modal-body">
		<form action="<?php echo HTTP_PATH .'inventory/stock_level/result'?>" method="POST" id="filter_form">
		<div class="col-xs-12 col-sm-12">
			<label for="company">Company:*</label>
			<select class="form-control input-sm" name="company_filter" id="company_filter">
				<option value=''>Select</option>
				<?php 
					foreach($companies as $company){
						echo "<option value='".$company->id."'>".$company->name."</option>";
					}
				?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-12">
			<label for="location">Location:*</label>
			<select class="form-control input-sm" name="location_filter" id="location_filter">
				<option value=''>Select</option>
				<?php 
					foreach($locations as $location){
						echo "<option value='".$location->location_name."'>".$location->location_name."</option>";
					}
				?>
			</select>
		</div>
	</div>
	<div class='modal-footer'>
		<input class="btn btn-danger pull-right" value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:20px">
		<input class="btn btn-success pull-right" type="submit" value="Filter" id="submitbtn" name="submitbtn"  style="width:100px; margin-left:30px; margin-top:20px;" onclick='javascript:submitForm()'>
	</div>
</form>

<script type="text/javascript">
function submitForm() {
	var company = $('#company_filter').val();
	var location = $('#location_filter').val();
	console.log(company);
	console.log(location);
    if(company=='' || location==''){
			toastr["warning"]("Please fill-out all required fields(*).<br/>","ABAS Says");
        	return false;
    }
	document.getElementById("filter_form").submit();
	return true;
}
</script>