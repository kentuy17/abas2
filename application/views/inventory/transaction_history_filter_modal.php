
<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		Filter: <?php echo ucwords($history_type) . " History";?>
	</div>
</div>
		<div class="panel-body">
			<form action="<?php echo HTTP_PATH .'inventory/transaction_history/' . $history_type ; ?>" method="POST" id="filter_form">
		
				<div class="col-xs-12 col-sm-6">
				<label for="date_from">From: </label>
				<input class="form-control input-sm" type="date" name="date_from" id="date_from" value="date_from"/>
				</div>
				<div class="col-xs-12 col-sm-6">
				<label for="date_to">To: </label>
				<input class="form-control input-sm" type="date" name="date_to" id="date_to" value="date_to"/>
				</div>

				<div class="col-xs-12 col-sm-6">
				<?php

					if($history_type=="issuance"){
						
						echo '<label for="filter">Issued For:</label>';
						echo '<select class="form-control" name="filter" id="filter">';
						echo '<option value=""></option>';
			
						foreach($vessels as $record){
							$vessel =$this->Abas->getVessel($record->id);
							echo '<option value="' . $vessel->id . '">' . $vessel->name . '</option>';
						}
		
						echo '</select>';
					}
					elseif($history_type=="delivery"){

						echo '<label for="filter">Supplier:</label>';
						echo '<select class="form-control" name="filter" id="filter">';
						echo '<option value=""></option>';
			
						foreach($suppliers as $record){
							$supplier =$this->Abas->getSupplier($record['id']);
							echo '<option value="' . $supplier['id'] . '">' . $supplier['name'] . '</option>';
						}
		
						echo '</select>';

					}
					elseif($history_type=="transfer"){
						echo '<label for="filter">Transferred by:</label>';
						echo '<input class="form-control" type="text" name="filter" id="filter">';
					}
					elseif($history_type=="return"){
						echo '<label for="filter">Returned From:</label>';
						echo '<select class="form-control" name="filter" id="filter">';
						echo '<option value=""></option>';
			
						foreach($vessels as $record){
							$vessel =$this->Abas->getVessel($record->id);
							echo '<option value="' . $vessel->id . '">' . $vessel->name . '</option>';
						}
		
						echo '</select>';
					}
				?>
				</div>
			
				<div class="col-xs-12 col-sm-6">
					<input class="btn btn-danger pull-right" value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:20px">
					<input class="btn btn-success pull-right" type="submit" value="Filter" id="submitbtn" name="submitbtn"  style="width:100px; margin-left:30px; margin-top:20px;" onclick='javascript:checkautoform()'>
				</div>
				
			</form>
		</div>
		<br>

<script type="text/javascript">

function checkautoform() {
		$("#submitbtn").visible=false;

		var msg="";
		var date_from = document.getElementById("date_from").value;
		var date_to = document.getElementById("date_to").value

		if (date_from!="" && date_to=="" || date_from=="" && date_to!="") {
			msg ="Please supply both date from and to. <br/>";
		}

		if(msg!="") {
			$("#btnSubmit").visible=true;
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			$("#btnSubmit").visible=true;

			$('body').addClass('is-loading'); 
			$('#modalDialog').modal('toggle'); 
			
			document.getElementById("filter_form").submit();
			return true;
		}

</script>