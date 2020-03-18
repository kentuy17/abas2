<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		Filter: Vessel Repairs Statistics

		<?php 
		//$this->Mmm->debug($vessels);?>
	</div>
</div>
		<div class="modal-body">
			<form action="<?php echo HTTP_PATH .'manager/vessel_repairs_statistics/result'?>" method="POST" id="filter_form">
				<div class="col-xs-12 col-sm-6">
				<label for="vessel">Vessel:* </label>
					<select name='vessel' id='vessel' class="form-control input-sm" required>
						<option value=''>Select</option>
						<?php

							foreach($vessels as $vessel) {
								echo "<option value='".$vessel->id."'>".$vessel->name."</option>";
							}
						?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6">
					<label for="date_from">Project Reference No: </label>
					<select id='project_reference' name='project_reference' class='form-control input-sm' required>
						<option value='' selected>Select</option>
					</select>
				</div>
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


 $('#vessel').change(function(){

 	 $.ajax({
         type:"POST",
         url:"<?php echo HTTP_PATH;?>manager/vessel_project_references/"+$(this).val(),
         success:function(data){

            var references = $.parseJSON(data); 

            $('#project_reference').find('option').remove().end().append('<option value="">Select</option>').val('');

            for(var i = 0; i < references.length; i++){
                var vessel = references[i];
                var option = $('<option />');
                option.attr('value', vessel.id).text(vessel.reference_number + " (" + vessel.approved_on + ")");
                $('#project_reference').append(option);
            }

         }

      });

 });

  $('#project_reference').change(function(){

 	 $.ajax({
         type:"POST",
         url:"<?php echo HTTP_PATH;?>manager/project_reference_dates/"+$(this).val(),
         success:function(data){

            var dates = $.parseJSON(data); 

            $("#date_from").val(dates.from_date);
            $("#date_to").val(dates.to_date);

         }

      });

 });

function submitForm() {

	var all_inputs = $('#filter_form').find('input').filter('[required]');
	
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