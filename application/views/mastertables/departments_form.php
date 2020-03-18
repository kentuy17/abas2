<?php

$form_action=	HTTP_PATH."mastertables/departments/insert";
$title="Add New Department";

$e	=	array(
	"name"=>"",
	"sorting"=>"",
	"accounting_code"=>"",
	"stat"=>"",
	"created_by"=>"",
	"modified_by"=>"",
	"created"=>"",
	"modified"=>""
);

if(isset($existing)) {
	$form_action	=	HTTP_PATH."mastertables/departments/update/".$existing['id'];
	$title			=	"Edit Department ";
	//$this->Mmm->debug($existing);
	$e	=	$existing;
}
?>


<div class="panel panel-primary">
	<div class= "panel-heading">
		<button type= "button" class ="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title"><?php echo $title; ?></h5>
	</div>
	</div>
	<div class="panel-body">
		<form action="<?php echo $form_action; ?>" role='form' method='POST' id='mastertables_departments_form' enctype='multipart/form-data'>

			<?php echo $this->Mmm->createCSRF(); ?>
			<div class='col-xs-12 col-sm-6'>
				<label for='name'>Department Name</label>
				<input type='text' id='name' name='name' placeholder='Department Name' class='form-control' value='<?php echo $e['name']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='sorting'>Sorting</label>
				<input type='text' id='sorting' name='sorting' placeholder='Sorting' class='form-control' value='<?php echo $e['sorting']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='accounting_code'>Accounting Code</label>
				<input type='text' id='accounting_code' name='accounting_code' placeholder='Accounting Code' class='form-control' value='<?php echo $e['accounting_code']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<div class="form-group col-xs-12 col-sm-12 pull-right">
				<input type='button' value='Submit' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
			</div>
		</form>

</div>
<script>
	function checkInput() {
		var msg="";
		var name=document.forms.mastertables_departments_form.name.value;
		if (name==null || name=="" || name=="Department Name") {
			msg+="Department name is required! <br/>";
		}
		var sorting=document.forms.mastertables_departments_form.sorting.value;
		if (sorting==null || sorting=="" || sorting=="Sorting") {
			msg+="Sorting is required! <br/>";
		}
		var accounting_code=document.forms.mastertables_departments_form.accounting_code.value;
		if (accounting_code==null || accounting_code=="" || accounting_code=="Accounting Code") {
			msg+="Accounting Code is required! <br/>";
		}
		if(msg!="") {
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			document.getElementById("mastertables_departments_form").submit();
			return true;
		}
	}
</script>