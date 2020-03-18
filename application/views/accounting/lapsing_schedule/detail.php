<div class='panel panel-primary'>
		<div class='panel-heading'>
			<div class='panel-title'>
				<text>Edit Asset Depreciation</text>
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
			</div>
		</div>
</div>
<form role="form" id="lapsingDetailForm" action="<?php echo HTTP_PATH.'accounting/lapsing_schedule/update/'.$lapsing_schedule_detail->id; ?>" name="lapsingDetailForm" method="post">
	<div class='panel-body'>
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="col-sm-12 col-xs-12">
				<center><label><?php echo "<h2>".$lapsing_schedule_detail->asset_code . "</h2><br>" . $lapsing_schedule_detail->item_name . " | " . $lapsing_schedule_detail->item_particular;?></label></center>
				<input type="hidden" name="detail_id" id="detail_id" class="form-control" value="<?php echo $lapsing_schedule_detail->id?>">
					<hr>
			</div>
				<div class="col-sm-6 col-xs-12">
					<label>Beginning Accumulated Depreciation:*</label>
					<input tyle="number" name="begin_accumulated_depreciation" id="begin_accumulated_depreciation" class="form-control" value="<?php echo $lapsing_schedule_detail->begin_accumulated_depreciation?>">
					<hr>
				</div>
				<div class="col-sm-6 col-xs-12">
					<label>Beginning Net Book Value:*</label>
					<input tyle="number" name="begin_net_book_value" id="begin_net_book_value" class="form-control" value="<?php echo $lapsing_schedule_detail->begin_net_book_value?>">
					<hr>
				</div>
			<div class="col-sm-12 col-xs-12">
				<label>Monthly Depreciations:*</label>
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>January:</label>
				<input tyle="number" name="january" id="january" class="form-control" value="<?php echo $lapsing_schedule_detail->january_depreciation?>">
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>July:</label>
				<input tyle="number" name="july" id="july" class="form-control" value="<?php echo $lapsing_schedule_detail->july_depreciation?>">
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>February:</label>
				<input tyle="number" name="february" id="february" class="form-control" value="<?php echo $lapsing_schedule_detail->february_depreciation?>">
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>August:</label>
				<input tyle="number" name="august" id="august" class="form-control" value="<?php echo $lapsing_schedule_detail->august_depreciation?>">
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>March:</label>
				<input tyle="number" name="march" id="march" class="form-control" value="<?php echo $lapsing_schedule_detail->march_depreciation?>">
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>September:</label>
				<input tyle="number" name="september" id="september" class="form-control" value="<?php echo $lapsing_schedule_detail->september_depreciation?>">
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>April:</label>
				<input tyle="number" name="april" id="april" class="form-control" value="<?php echo $lapsing_schedule_detail->april_depreciation?>">
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>October:</label>
				<input tyle="number" name="october" id="october" class="form-control" value="<?php echo $lapsing_schedule_detail->october_depreciation?>">
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>May:</label>
				<input tyle="number" name="may" id="may" class="form-control" value="<?php echo $lapsing_schedule_detail->may_depreciation?>">
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>November:</label>
				<input tyle="number" name="november" id="november" class="form-control" value="<?php echo $lapsing_schedule_detail->november_depreciation?>">
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>June:</label>
				<input tyle="number" name="june" id="june" class="form-control" value="<?php echo $lapsing_schedule_detail->june_depreciation?>">
			</div>
			<div class="col-sm-6 col-xs-12">
				<label>December:</label>
				<input tyle="number" name="december" id="december" class="form-control" value="<?php echo $lapsing_schedule_detail->december_depreciation?>">
			</div>
	</div>
	<div class='modal-footer'>
		<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: checkform()' />
		<input type='button' class='btn btn-danger btn-m' value='Discard' data-dismiss='modal'>
	</div>
</form>

<script>
	function checkform() {
		var msg="";
		var begin_accumulated_depreciation=document.getElementById("begin_accumulated_depreciation").value;
		if (begin_accumulated_depreciation==null || begin_accumulated_depreciation=="") {
			msg+="Beginning Accumulated Depreciation is required! <br/>";
		}
		var begin_net_book_value=document.getElementById("begin_net_book_value").value;
		if (begin_net_book_value==null || begin_net_book_value=="") {
			msg+="Beginning Net Book Value is required! <br/>";
		}
		if(msg!="") {
			toastr['error'](msg, "You have missing input!");
			return false;
		}
		else {
			$('body').addClass('is-loading');
			$('#modalDialogNorm').modal('toggle');
			document.getElementById("lapsingDetailForm").submit();
			return true;
		}
	}
</script>