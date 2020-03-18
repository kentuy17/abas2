<?php
$company_options	=	"";
if(isset($companies)) {
	$company_options=	"<option value=''></option>";
	foreach($companies as $c){
		$company_options.=	"<option value='".$c->id."'>".$c->name."</option>";
	}
}
$department_options	=	"";
if(isset($departments)) {
	$department_options=	"<option value=''></option>";
	foreach($departments as $c){
		$department_options.=	"<option value='".$c->id."'>".$c->name."</option>";
	}
}

$division_options	=	"";
if(isset($divisions)) {
	$division_options=	"<option value=''></option>";
	foreach($divisions as $d){
		$division_options.=	"<option value='".$d->id."'>".$d->name."</option>";
	}
}

$position_options	=	"";
if(isset($positions)) {
	$position_options=	"<option value=''></option>";
	foreach($positions as $p){
		$position_options.=	"<option value='".$p->id."'>".$p->name."</option>";
	}
}


$vessel_id	= "";
$vesseloptions	=	"";
if(!empty($vessels)) {
	foreach($vessels as $s) {
		$vesseloptions	.=	"<option ".($vessel_id==$s->id?"SELECTED":"")." value='".$s->id."'>".$s->name."</option>";
	}
}
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		Filter: Employees Summary
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
	</div>
</div>
<form class="form" role="form"  action="<?php echo HTTP_PATH.'hr/employee_report'; ?>" method="post" enctype='multipart/form-data'>
	<?php echo $this->Mmm->createCSRF(); ?>
	<div class="panel-body">
			
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="vessel">Company:</label>
					<div>
						<select class="form-control" name="company" id="company">
						<?php echo $company_options; ?>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="vessel">Assigned to:</label>
					<div>
						<select class="form-control" name="vessel" id="vessel" >
							<option></option>
							<?php echo $vesseloptions; ?>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="group">Group:</label>
					<div>
						<select class="form-control" name="group" id="group">
							<option></option>
							<option value='Sea-based'>Sea-based</option>
							<option value='Land-based'>Land-based</option>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="division">Division:</label>
					<div>
						<select class="form-control" name="division" id="division">
							<?php echo $division_options; ?>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="department">Department:</label>
					<div>
						<select class="form-control" name="department" id="department">
							<?php echo $department_options; ?>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="section">Section:</label>
					<div>
						<select class="form-control" name="section" id="section">
							<option value='' selected></option>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="subsection">Sub-section:</label>
					<div>
						<select class="form-control" name="subsection" id="subsection">
							<option value='' selected></option>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="position">Position:</label>
					<div>
						<select class="form-control" name="position" id="position">
							<option></option>
							<?php echo $position_options; ?>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="civilstatus">Civil Status:</label>
					<div>
						<select class="form-control" name="civil_status" id="civil_status">
							<option></option>
							<option value='Single'>Single</option>
							<option value='Married'>Married</option>
							<option value='Separated'>Separated</option>
							<option value='Widowed'>Widowed</option>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="gender">Gender:</label>
					<div>
						<select class="form-control" name="gender" id="gender">
							<option></option>
							<option value='Male'>Male</option>
							<option value='Female'>Female</option>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="empstat">Employment Status:</label>
					<div>
						<select class="form-control" name="empstat" id="empstat">
							<option></option>
							<option value="Contractual">Contractual</option>
							<option value="Probationary">Probationary</option>
							<option value="Regular">Regular</option>
							<option value="On-leave">On-leave</option>
							<option value="AWOL">AWOL</option>
							<option value="Preventive Suspension">Preventive Suspension</option>
							<option value="Suspended">Suspended</option>
							<option value="Resigned">Resigned</option>
							<option value="Retired">Retired</option>
							<option value="Terminated">Terminated</option>
							<option value="Separated">Separated</option>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="atmstat">Has Bank Account/ATM?:</label>
					<select class="form-control" name="atmstat" id="atmstat">
						<option></option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="emplstat">Employee Status:</label>
					<select class="form-control" name="emplstat" id="emplstat">
						<option></option>
						<option value="Active">Active</option>
						<option value="Inactive">Inactive</option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="abasaccount">Has ABAS Account?:</label>
					<select class="form-control" name="abasaccount" id="abasaccount">
						<option></option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
				</div>

				<div id="date_hired" class="col-xs-12 col-sm-12 col-md-12">
					<hr>
					<label for="date_hired">Hire date:</label>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					From: <input class="form-control datepicker" type="date" name="from_date" id="from_date" />
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					To: <input class="form-control datepicker" type="date" name="to_date" id="to_date" />
				</div>
			
	</div>
	<div class="modal-footer">
		<input class="btn btn-success btn-m" type="submit"  value="Filter" id="submitbtn">
		<input  type="button" class="btn btn-danger btn-m"  value="Cancel" data-dismiss="modal">
	</div>
</form>
<script type="text/javascript">
$('#department').change(function(){   
  var department_id = $(this).val();
	  if(department_id!=""){
	  	$('#section').prop('disabled',false);
		  $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH;?>hr/set_sections/"+department_id,
		     success:function(data){
		        var sections = $.parseJSON(data);
		        $('#section').find('option').remove().end().append('<option value=""></option>').val('');
		        if(sections.length>0){
					for(var i = 0; i < sections.length; i++){
			       		var section = sections[i];
			       		var option = $('<option />');
					    option.attr('value', section.id).text(section.name);
					    $('#section').append(option);
			        }
			    }else{
			    	$('#subsection').find('option').remove();
			    }
		     } 	
		  });
	  }else{
	  		$('#subsection').find('option').remove();
	  }
});

$('#section').change(function(){   
  var section_id = $(this).val();
	  if(section_id!=""){
	  	$('#subsection').prop('disabled',false);
		  $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH;?>hr/set_subsections/"+section_id,
		     success:function(data){
		        var subsections = $.parseJSON(data);
		        $('#subsection').find('option').remove().end().append('<option value=""></option>').val('');
		        if(subsections.length>0){
					for(var i = 0; i < subsections.length; i++){
			       		var subsection = subsections[i];
			       		var option = $('<option />');
					    option.attr('value', subsection.id).text(subsection.name);
					    $('#subsection').append(option);
			        }
		    	}
		     } 	
		  });
	  }
});
</script>