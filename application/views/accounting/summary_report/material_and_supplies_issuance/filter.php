<?php
$companyoptions	=	"";
foreach($companies as $company){
	$companyoptions	.=	'<option value="' . $company->id . '">' . $company->name . '</option>';
}
$locationoptions	=	"";
foreach($locations as $location){
	$locationoptions	.=	'<option value="' . $location['location_name'] . '">' . $location['location_name'] . '</option>';
}
?>
<div class="panel panel-primary">
	<div class="panel-heading" style="min-height">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		Filter: Material and Supplies Issuances
	</div>
</div>
<form action="<?php echo HTTP_PATH .'accounting/summary_report/' . $type ; ?>" method="POST" id="filter_form">
		<div class="panel-body">
				<?php if($type=="MSIS" || $type=="MSIS_consolidated"): ?>
					<div class="col-xs-12 col-sm-12">
						<label>Company:</label>
						<select class="form-control" name="company" id="company">
							<option value="">Select</option>
							<?php echo $companyoptions; ?>
						</select>
					</div>
					<div class="col-xs-12 col-sm-6">
						<label>Issued For:</label>
						<select class="form-control filter" name="filter" id="filter">
							<option value="">Select</option>
						</select>
					</div>
					<div class="col-xs-12 col-sm-6">
						<label>Location:</label>
						<select class="form-control" name="location" id="location">
						<option value="">Select</option>
							<?php echo $locationoptions; ?>
						</select>
					</div>
				<?php endif; ?>

				<div class="col-xs-12 col-sm-6">
					<label for="date_from">From: </label>
					<input class="form-control input-sm" type="date" name="date_from" id="date_from" value="date_from"/>
				</div>
					<div class="col-xs-12 col-sm-6">
					<label for="date_to">To: </label>
				<input class="form-control input-sm" type="date" name="date_to" id="date_to" value="date_to"/>
				</div>
		</div>
				
		<div class="modal-footer">
			<input class="btn btn-danger pull-right" value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:20px">
			<input class="btn btn-success pull-right" type="submit" value="Filter" id="submitbtn" name="submitbtn"	style="width:100px; margin-left:0px; margin-top:20px;" onclick='javascript:checkautoform()'>
		</div>
</form>

<script type="text/javascript">

$('#company').change(function()
	{

		$.ajax({
			type:"POST",
			url:"<?php echo HTTP_PATH;?>accounting/vessels_by_company/"+$(this).val(),
			success:function(data){
					var vessels = $.parseJSON(data);

					$('#filter').find('option').remove().end().append('<option value="">Select</option>').val('');

					for(var i = 0; i < vessels.length; i++){
						var vessel = vessels[i];
						var option = $('<option />');
					option.attr('value', vessel.id).text(vessel.name);
					$('#filter').append(option);
					}

			}

		});
	});

function checkautoform() {

		var msg="";
		var date_from = $("#date_from").val();
		var date_to = $("#date_to").val();

		if (date_from!="" && date_to=="" || date_from=="" && date_to!="" || date_from=="" && date_to=="") {
			msg ="Please supply both date from and to. <br/>";
		}

		if(msg!="") {
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {

			$('body').addClass('is-loading');
			$('#modalDialog').modal('toggle');

			document.getElementById("filter_form").submit();
			return true;
		}
}
</script>