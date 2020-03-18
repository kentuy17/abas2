
<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		Filter: Statement of Account
	</div>
</div>
		<div class="panel-body">
			<form action="<?php echo HTTP_PATH .'statements_of_account/filter_SOA_aging_report'; ?>" method="POST" id="filter_form">
		
				<div class="col-xs-12 col-m-12 col-sm-6">
					<label for="date_from"> Date Received by Client - From: </label>
					<input class="form-control input-sm" type="date" name="date_from" id="date_from" value="date_from"/>
				</div>
					<div class="col-xs-12 col-sm-6">
					<label for="date_to"> To: </label>
				<input class="form-control input-sm" type="date" name="date_to" id="date_to" value="date_to"/>
				</div>

				<div class="col-xs-12 col-m-12 col-sm-12">
				<?php

						echo '<label>Company:</label>';
						echo '<select class="form-control" name="company" id="company">';
						echo '<option value="">Select</option>';
			
						foreach($companies as $company){
							echo '<option value="' . $company->id . '">' . $company->name . '</option>';
						}

						echo '</select>';
				?>
				</div>
				
				<div class="col-xs-12 col-m-12 col-sm-12">
				<?php
						
						echo '<label>Client:</label>';
						echo '<select class="form-control" name="client" id="client">';
						echo '<option value="">Select</option>';
			
						foreach($clients as $client){
							echo '<option value="' . $client['id'] . '">' . $client['company'] . '</option>';
						}
		
						echo '</select>';

				?>
				</div>

				<div class="col-xs-12 col-m-12 col-sm-12">
					<input class="btn btn-danger  pull-right" value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:20px">
					<input class="btn btn-success  pull-right" type="submit" value="Filter" id="submitbtn" name="submitbtn"  style="width:100px; margin-left:0px; margin-top:20px;" onclick='javascript:checkautoform()'>
				</div>
				
			</form>
		</div>
		<br>

<script type="text/javascript">


function checkautoform() {

		var msg="";
		var date_from = document.getElementById("date_from").value;
		var date_to = document.getElementById("date_to").value

		if (date_from!="" && date_to=="" || date_from=="" && date_to!="") {
			msg ="Please supply both date from and to. <br/>";
		}

		if(msg!="") {
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			document.getElementById("filter_form").submit();
			return true;
		}
}
</script>