<?php
	$form_action	= HTTP_PATH."mastertables/positions/insert";
	$title="Add New Position";
	//$department_id		=	"";
	$depid	=	$this->db->query("SELECT * FROM departments");
	$depid	=	$depid->result_array();
	$department_id_option	=	"";

	$e = array(
	"department_id"=>"",
	"name"=>"",
	"sorting"=>"",
	"stat"=>"",
	"created_by"=>"",
	"modified_by"=>"",
	"created"=>"",
	"modified"=>""
	);


	if (isset($existing)){
	$form_action= HTTP_PATH."mastertables/positions/update/".$existing['id'];
	$title="Edit Position";
	$e = $existing;
	}

	if(!empty($depid))	{
		foreach($depid as $departmentid)	{
			$department_id_option	.="<option value='".$departmentid['id']."' ".(($e['department_id']==$departmentid['id'])?"SELECTED":"") .">".$departmentid['name']."</option>";
		}
	}
	
?>
<div class="panel panel-primary">
	<div class= "panel-heading" style="min-height">
		<button type= "button" class ="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title"><?php echo $title; ?></h5>
	</div>
</div>
	<div class= "panel-body">
		<form action="<?php echo $form_action; ?>" role='form' method='POST' id='mastertables_positions_form' enctype='multipart/form-data'>
		
		
		<div class= 'form-group col-xs-12 col-sm-6'>
			<label for ='department_id'>Department</label>
			<select class='form-control' id='department_id' name='department_id' >
				<option value=''></option>
				<?php echo $department_id_option; ?>
			</select>
		</div>
			<div class= 'form-group col-xs-12 col-sm-6'>
				<label for ='name'>Name</label>
				<input type='text' id='name' class='form-control' name='name' placeholder='Name' value='<?php echo $e['name']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for='sorting'>Sorting</label>
				<input type='text' class='form-control' name='sorting' value='<?php echo $e['sorting']; ?>'/>
			</div>
			<div class='form-group col-xs-12 col-sm-6'>
				<label for= 'stat'>Stat</label>
				<input type='text' class='form-control' placeholder= '1' name='stat' value='<?php echo $e['stat'] ;?>'/>
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
			var department_id=document.forms.mastertables_positions_form.department_id.selectedIndex;
			if (department_id==null || department_id=="")	{
				msg+="Department ID is required! <br/>";
			}
			var name=document.forms.mastertables_positions_form.name.value;
			if (name==null || name== "" || name=="Name")	{
				msg+="Name is required! <br/>";
			}
			if(msg!="")	{
				toastr["warning"] (msg,"ABAS Says");
				return false;
			}
			else {
				document.getElementById("mastertables_positions_form").submit();
				return true;
			}
		}
</script>