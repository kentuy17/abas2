<div class="panel panel-primary">
	<div class="panel-heading">
		Filter: Bonus/13th Month Pay
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2>
	</div>
</div>
	<div class="panel-body">
		<form class="form-horizontal" id="bonus_filter_form" role="form"  action="<?php echo HTTP_PATH.'hr/bonus_report/report'; ?>" method="post" enctype='multipart/form-data'>
		<?php echo $this->Mmm->createCSRF(); ?>
			<div class="panel">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label >Released Date:</label> 
					<input class="form-control" type="date" name="release_date" id="release_date" />
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label>Type:</label>
					<select class="form-control" name="type" id="type" >
						<option></option>
						<option value="Bonus">Bonus</option>
						<option value="13th Month - Full">13th Month - Full</option>
						<option value="13th Month - 1st half">13th Month - 1st half</option>
						<option value="13th Month - 2nd half">13th Month - 2nd half</option>
					</select>
				</div>
				<div class='col-xs-12 col-xs-12 col-lg-12 clearfix'><br/><br/></div>
				<div class="col-xs-12 col-sm-12">
					<span class="pull-right">
					<input class="btn btn-success btn-m" type="button" onclick="javascript: validateForm()"  value="Filter" id="submitbtn">
					<input  type="button" class="btn btn-danger btn-m"  value="Cancel" data-dismiss="modal">
					</span>
				</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	function validateForm(){
		var release_date = $('#release_date').val();
    	var typex = $('#type').val();
    	if(release_date=="" || typex==""){
        	toastr["error"]("Please fill Release Date and/or Type.", "ABAS says:");
        	return false;
        }else{
        	$("#bonus_filter_form").submit();
        }
    }
</script>