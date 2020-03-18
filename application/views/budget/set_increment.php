<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title">Set Percentage Increment for all Accounts
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<div class="panel-body">
	<form action="<?=HTTP_PATH.'corporate_services/confirm/increment'?>" role="form" method="POST" id="request_for_payment_form" enctype="multipart/form-data">
		<div class="panel panel-info">
			<div class="panel-body" id="summary_container">
				
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label>Increment:</label>
					<input type="number" name="percentage" class="form-control" placeholder="0%" required>
					Warning: This will reset the percentage increment for all accounts including the budget that is already approved for the current year.
				</div>
			</div>
		</div>	
		<div class="col-xs-12 col-sm-12 col-lg-12"s>
			<br>
			<span class="pull-right">
				
				<input type="submit" value="Update" class="btn btn-success btn-m"/>
				<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
			</span>
		</div>
	</form>
</div>
