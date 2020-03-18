<div class="panel-group">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h4 class="panel-title">
				SMS Report Simulator
				<button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
			</h4>
		</div>
		<div class="panel-body">
			<form name="frmTool" action="<?php echo HTTP_PATH.'sms/receive'; ?>" method="post">
				<div class="col-sm-12 col-md-4">
					<label for="data_source">Mobile Number</label>
					<div>
						<input class="form-control" type="text" name="data_source" id="data_source" value="09771234567" />
					</div>
				</div>
				<div class="col-sm-12 col-md-4">
					<label for="location">GPS Coordinates</label>
					<div>
						<input class="form-control" type="text" name="LOCATION" id="location" value="14.564828471744454,121.00910906926102" />
					</div>
				</div>
				<div class="col-sm-12 col-md-4">
					<label for="location">Report Type</label>
					<select class="form-control" name="raw_data">
						<option value="AV">Vessel Report</option>
						<option value="AVM">Maintenance Report</option>
						<option value="PO">Port Operation</option>
						<option value="WO">Warehouse Operation</option>
						<option value="PUR">Purchasing</option>
						<option value="TRU">Trucking</option>
						<option value="AVBTS">Tracking Signal</option>
					</select>
				</div>
				<?php for($x=0; $x<5; $x++): ?>
					<div class="col-sm-6 col-md-6">
						<label for="index<?php echo $x; ?>">Index</label>
						<input class="form-control" type="text" name="index<?php echo $x; ?>" id="index<?php echo $x; ?>" value="" />
					</div>
					<div class="col-sm-6 col-md-6">
						<label for="value<?php echo $x; ?>">Value</label>
						<input class="form-control" type="text" name="value<?php echo $x; ?>" id="value<?php echo $x; ?>" value="" />
					</div>
					<script>
					var index = document.getElementById("index<?php echo $x; ?>");
					var value = document.getElementById("value<?php echo $x; ?>");
					document.getElementById("index<?php echo $x; ?>").onchange=function(){document.getElementById("value<?php echo $x; ?>").name = document.getElementById("index<?php echo $x; ?>").value;};
					</script>
				<?php endfor; ?>
				<div class="clearfix">&nbsp;</div>
				<div class="col-lg-12">
					<input class="btn btn-success btn-sm btn-block" type="submit"  value="Send SMS Report" id="submitbtn">
				</div>
			</form>
		</div>
	</div>
</div>