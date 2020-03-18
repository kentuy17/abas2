<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class "modal title">Payment</h5>
	</div>
	<div class="panel-body">
		<form action= method="POST">
			<div class="form-group col-xs-6">
				<input type="text" class="col-xs-12" name="soa" id="soa" placeholder="SOA #"/>
			</div>
			<div class="form-group col-xs-6">
				<input type="text" class="col-xs-12" name="date" id="date" placeholder="Date of Payment"/>
			</div>
			<div class="form-group col-xs-6">
				<input type="text" class="col-xs-12" name="mode" id="mode" placeholder="Mode of Payment"/>
			</div>
			<div class="form-group col-xs-6">
				<input type="text" class="col-xs-12" name="check_no" id="check_no" placeholder="Check Number"/>
			</div>
			<div class="form-group col-xs-6">
				<input type="text" class="col-xs-12" name="amount" id="amount" placeholder="Amount"/>
			</div>
			<div class="form-group col-xs-6">
				<input type="text" class="col-xs-12" name="remark" id="remark" placeholder="Remarks"/>
			</div>
			<div class="form-group pull-right">
				<input type="button" class="btn btn-info" name="cancel" id="cancel" value="Cancel" data-dismiss="modal"/>
				<input type="button" class="btn btn-info" name="save" id="save" value="Save"/>
			</div>
		</form>
	</div>
</div>
<script>
	$("#date").datepicker({ changeMonth: true, changeYear: true, yearRange:"-100:+0", dateFormat:"yy-mm-dd"});
</script>