<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title">Edit percentage for <?=$ac_accounts->name?>
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<div class="panel-body">
	<form action="<?=HTTP_PATH.'manager/update_percentage/'.$account->id?>" role="form" method="POST" id="request_for_payment_form" enctype="multipart/form-data">
		<div class="panel panel-info">
			<div class="panel-body" id="summary_container">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label>From:</label>
					<input type="number" class="form-control" value="<?=$account->percentage?>" disabled>
				</div>
				<br/><br/><br/><br/>
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label>To:</label>
					<input type="number" name="percentage" class="form-control" placeholder="0%" value="<?=$account->percentage?>">
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
