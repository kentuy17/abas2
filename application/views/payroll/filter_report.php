<?php
	$companyoptions	=	"";
	if(!empty($companies)) {
		foreach($companies as $c) {
			$companyoptions	.=	"<option value='".$c->id."'>".$c->name."</option>";
		}
	}
	unset($c);
	$monthoptions	=	"";
	for($x=1; $x<=12; $x++) {
		$monthoptions.=	"<option value='".$x."' ".(date("F", strtotime("1970-".$x."-01")) == date("F") ? "SELECTED" : "")." >".date("F", strtotime("1970-".$x."-01"))."</option>";
	}
	unset($x);
	$yearoptions	=	"";
	for($y=2016; $y<=date("Y"); $y++) {
		$yearoptions.=	"<option value='".$y."' ".(date("Y") == $y ? "SELECTED" : "").">".$y."</option>";
	}
	unset($y);

?>
<div class="panel panel-primary">
	<div class="panel-heading">
		Filter: <?php echo ucfirst($type)?>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2>
	</div>
</div>
	<div class="panel-body">
		<div class="tab-content">
		<?php if($type=='alphalist'): ?>
			<form action="<?php echo HTTP_PATH."payroll/alphalist_report"; ?>" method="POST" enctype="multipart/form-data" name="alphalist-form" id="alphalist-form">
				<?php echo $this->Mmm->createCSRF(); ?>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<label class="control-label">Company*</label>
					<select class="form-control" name="company" required>
						<option value="">-</option>
						<?php echo $companyoptions; ?>
					</select>
				</div>
				<!--<div class="col-lg-6 col-md-6 col-sm-12">
					<label class="control-label">Month*</label>
					<select class="form-control" name="month" required>
						<?php //echo $monthoptions; ?>
					</select>
				</div>-->
				<div class="col-lg-12 col-md-12 col-sm-12">
					<label class="control-label">Year*</label>
					<select class="form-control" name="year" required>
						<?php echo $yearoptions; ?>
					</select>
				</div>
				<div class='col-xs-12 col-xs-12 col-lg-12 clearfix'><br/><br/></div>
				<div class="col-xs-12 col-sm-12">
					<span class="pull-right">
					<input class="btn btn-success btn-m" type="submit"  value="Filter" id="submitbtn">
					<input  type="button" class="btn btn-danger btn-m"  value="Cancel" data-dismiss="modal">
					</span>
				</div>
			</form>
		<?php endif ?>
		<?php if($type=='annualization'): ?>
				<form action="<?php echo HTTP_PATH."payroll/annualization_report"; ?>" method="POST" enctype="multipart/form-data" name="alphalist-form" id="alphalist-form">
					<div class="col-lg-12">
						<label class="control-label">Employee*</label>
						<input type="text" id="employee_label" class="form-control ui-autocomplete-input" name="employee-label" required/>
						<input type="text" id="employee_id" class="hide" name="employee"/>
					</div>
					<div class='col-xs-12 col-xs-12 col-lg-12 clearfix'><br/><br/></div>
					<div class="col-xs-12 col-sm-12">
						<span class="pull-right">
						<input class="btn btn-success btn-m" type="submit"  value="Filter" id="submitbtn">
						<input  type="button" class="btn btn-danger btn-m"  value="Cancel" data-dismiss="modal">
						</span>
					</div>
				</form>
		<?php endif ?>
		<?php if($type=='payroll'): ?>
				<form action="<?php echo HTTP_PATH."payroll/payroll_report"; ?>" method="POST" enctype="multipart/form-data" name="payroll-form" id="payroll-form">
					<div class="col-lg-8">
						<label class="control-label">Employee*</label>
						<input type="text" id="employee_label2" class="form-control ui-autocomplete-input" name="employee-label" required/>
						<input type="text" id="employee_id2" class="hide" name="employee2"/>
					</div>
					<div class="col-lg-4">
						<label class="control-label">Year*</label>
						<select class="form-control" name="year2" required>
							<?php echo $yearoptions; ?>
						</select>
					</div>
					<div class='col-xs-12 col-xs-12 col-lg-12 clearfix'><br/><br/></div>
					<div class="col-xs-12 col-sm-12">
						<span class="pull-right">
						<input class="btn btn-success btn-m" type="submit"  value="Filter" id="submitbtn">
						<input  type="button" class="btn btn-danger btn-m"  value="Cancel" data-dismiss="modal">
						</span>
					</div>
				</form>
		<?php endif ?>
		<?php if($type=='contribution'): ?>
			<form action="<?php echo HTTP_PATH."payroll/contribution_report"; ?>" method="POST" enctype="multipart/form-data" name="contribution-form" id="contribution-form">
				<?php echo $this->Mmm->createCSRF(); ?>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<label class="control-label">Company*</label>
					<select class="form-control" name="company" id="company" required>
						<option value="">-</option>
						<?php echo $companyoptions; ?>
					</select>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12">
					<label class="control-label">Payroll Period*</label>
					<select class="form-control" name="payroll_period" id="payroll_period" required>
						<option value="">Select</option>
					</select>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<label class="control-label">Type*</label>
					<select class="form-control" name="type" required>
						<option value=''>Select</option>
						<option value='SSS'>SSS</option>
						<option value='PhilHealth'>PhilHealth</option>
						<option value='Pag-ibig'>Pag-ibig</option>
					</select>
				</div>
				<div class='col-xs-12 col-xs-12 col-lg-12 clearfix'><br/><br/></div>
				<div class="col-xs-12 col-sm-12">
					<span class="pull-right">
					<input class="btn btn-success btn-m" type="submit"  value="Filter" id="submitbtn">
					<input  type="button" class="btn btn-danger btn-m"  value="Cancel" data-dismiss="modal">
					</span>
				</div>
			</form>
		<?php endif ?>
		<?php if($type=='loan'): ?>
				<form action="<?php echo HTTP_PATH."payroll/loan_report"; ?>" method="POST" enctype="multipart/form-data" name="loan-form" id="loan-form">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8">
						<label class="control-label">Company*</label>
						<select class="form-control" name="company" id="company" required>
							<option value="">All</option>
							<?php echo $companyoptions; ?>
						</select>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<label class="control-label">Loan Type*</label>
						<select class="form-control" name="loantype" id="loantype" required>
							<option value="All">All</option>
							<option value="Cash Advance">Cash Advance</option>
							<option value="ELF">ELF</option>
							<option value="PagIbig">Pag-Ibig</option>
							<option value="SSS">SSS</option>
						</select>
					</div>
					<div class='col-xs-12 col-xs-12 col-lg-12 clearfix'><br/><br/></div>
					<div class="col-xs-12 col-sm-12">
						<span class="pull-right">
						<input class="btn btn-success btn-m" type="submit"  value="Filter" id="submitbtn">
						<input  type="button" class="btn btn-danger btn-m"  value="Cancel" data-dismiss="modal">
						</span>
					</div>
				</form>
		<?php endif ?>
		</div>
	</div>

<script type="text/javascript">

	$('#company').change(function(){
		
		var company = $(this).val();
		var payroll_period = $(this).val();

		//Ajax to fill SOA
		$.ajax({
		 type:"POST",
		 url:"<?php echo HTTP_PATH;?>payroll/get_payroll_periods_per_company/"+company,
		 success:function(data){

		    var payroll = $.parseJSON(data);    

		    console.log(company);
		    	$('#payroll_period').find('option').remove().end().append('<option value="">Select</option>').val('');

		        for(var i = 0; i < payroll.length; i++){
		       		var py = payroll[i];
		       		var option = $('<option />');

				    option.attr('value',py.id).text(py.payroll_date + " - " +py.payroll_coverage);
				    $('#payroll_period').append(option);
		        }
		}
		});
	});

	$(document).ready(function () {
		$( "#employee_label" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>hr/employee_autocomplete_list",
			minLength: 2,
			search: function(event, ui) {
				toastr['info']('Loading, please wait...');
			},
			response: function(event, ui) {
				toastr.clear();
			},
			select: function( event, ui ) {
				$( "#employee_label" ).val( ui.item.label );
				$( "#employee_id" ).val( ui.item.value );
				return false;
			}
		});
		$( "#employee_label2" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>hr/employee_autocomplete_list",
			minLength: 2,
			search: function(event, ui) {
				toastr['info']('Loading, please wait...');
			},
			response: function(event, ui) {
				toastr.clear();
			},
			select: function( event, ui ) {
				$( "#employee_label2" ).val( ui.item.label );
				$( "#employee_id2" ).val( ui.item.value );
				return false;
			}
		});
	});

	var month_name = function(dt){
		mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
	  return mlist[dt.getMonth()];
	};
</script>
