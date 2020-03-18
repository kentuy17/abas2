<?php

$form_action=	HTTP_PATH."mastertables/salary_grades/insert";
$title="Add New Salary Grade";

$e	=	array(
	"grade"=>"",
	"rate"=>"",
	"level"=>"",
	"stat"=>""
);

if(isset($existing)) {
	$e				=	$existing;
	$form_action	=	HTTP_PATH."mastertables/salary_grades/update/".$existing['id'];
	$title			=	"Edit Salary Grade ".$e['grade'];
}
?>


<div class="panel panel-primary">
	<div class= "panel-heading" style="min-height">
		<button type= "button" class ="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title"><?php echo $title; ?></h5>
	</div>
</div>
	<div class="panel-body">
		<form action="<?php echo $form_action; ?>" role='form' method='POST' id='mastertables_salarygrade_form' enctype='multipart/form-data'>

			<?php echo $this->Mmm->createCSRF(); ?>
			<div class='col-xs-12 col-sm-12'>
				<label for='name'>Grade</label>
				<input type='text' id='grade' name='grade' placeholder='Grade' class='form-control' value='<?php echo $e['grade']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='sorting'>Rate</label>
				<input type='text' id='rate' name='rate' placeholder='Rate' class='form-control' value='<?php echo $e['rate']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='stat'>Level</label>
				<input type='text' id='level' name='level' placeholder='Level' class='form-control' value='<?php echo $e['level']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<input type='button' value='Submit' name='btnSubmit' id='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
			</div>
		</form>
</div>
<script>
	function checkInput() {
		var msg="";
		var grade=document.forms.mastertables_salarygrade_form.grade.value;
		if (grade==null || grade=="" || grade=="Salary Grade") {
			msg+="Grade is required! <br/>";
		}
		var rate=document.forms.mastertables_salarygrade_form.rate.value;
		if (rate==null || rate=="" || rate=="Rate") {
			msg+="Rate is required! <br/>";
		}
		var level=document.forms.mastertables_salarygrade_form.level.value;
		if (level==null || level=="" || level=="Level") {
			msg+="Level is required! <br/>";
		}
		if(msg!="") {
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			document.getElementById("mastertables_salarygrade_form").submit();
			return true;
		}
	}
</script>