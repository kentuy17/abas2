<?php

$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $c) {
		$company_options	.=	"<option value='".$c->id."'>".$c->name."</option>";
	}
	unset($c);
}

?>

<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		Filter: Accounting Entries
	</div>
</div>
<form action="<?php echo HTTP_PATH.'accounting/accounting_entries_summary_report/result'; ?>" method="POST" id="filter_form">
		<div class="panel-body">

				<div class="col-xs-12 col-sm-12">
					<label for="company">Company: </label>
					<select class="form-control input-sm" id="company" name="company" required>
						<?php echo $company_options;?>
					</select>
				</div>
		
				<div class="col-xs-12 col-sm-6">
					<label for="date_from">From: </label>
					<input class="form-control input-sm" type="date" name="date_from" id="date_from" required/>
				</div>
				
				<div class="col-xs-12 col-sm-6">
					<label for="date_to">To: </label>
					<input class="form-control input-sm" type="date" name="date_to" id="date_to" required/>
				</div>

				<div class="col-xs-12 col-sm-6">
					<label for="status">Status: </label>
					<select class="form-control input-sm" name="status" id="status" required>
						<option value='posted' selected>Posted</option>
						<option value='unposted'>Unposted</option>
					</select>
				</div>
		</div>
		<div class="modal-footer">
			<input class="btn btn-danger pull-right" value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:20px">
			<input class="btn btn-success pull-right" type="submit" value="Filter" id="submitbtn" name="submitbtn"  style="width:100px; margin-left:30px; margin-top:20px;" onclick='javascript:checkautoform()'>
		</div>
</form>

<script type="text/javascript">

function checkautoform() {

		console.log($("#company").val());

		var msg="";
		var company_id = $("#company").val();
		var date_from = $("#date_from").val();
		var date_to = $("#date_to").val();

		if(company_id==''){
			msg +="Please select a company. <br/>";
		}

		if (date_from=="" || date_to=="") {
			msg +="Please supply both posted on date from and to. <br/>";
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