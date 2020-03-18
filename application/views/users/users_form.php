

<?php
$signature=$username=$lastname=$firstname=$middlename=$email=$role=$user_location=$employee="";
$formaction		=	HTTP_PATH.'users/insert';
//$formaction		=	HTTP_PATH.'users/myFunction';
$title			=	"Add New User";
if(isset($existing)) {
	// echo "<pre>";print_r($employee_record);echo "</pre>";
	$title		=	"Edit User";
	$e			=	$existing;
	$formaction	=	HTTP_PATH.'users/update/'.$e->id;
	$username	=	$e->username;
	$lastname	=	$e->last_name;
	$firstname	=	$e->first_name;
	$middlename	=	$e->middle_name;
	$role		=	$e->role;
	$user_location	=	$e->user_location;
	$email		=	$e->email;
	$signature	=	LINK."assets/images/digitalsignatures/".$e->signature;
	$signature	=	"<img class='img-responsive' src='".$signature."' />";
	$employee 	=	$hr_name;
}
?>

<form class="form-horizontal" role="form" id="employee_info" name="employee_info"  action="<?php echo $formaction; ?>" method="post" enctype='multipart/form-data'>
	<?php echo $this->Mmm->createCSRF(); ?>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-primary">
			<div class='panel-heading'><h2 class="panel-title">
				<?php echo $title; ?>
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2>
			</div>
		</div>
		<div class="panel-body">
			<div class="col-xs-12 col-sm-6">
				<label for="last_name">Last Name:</label>
				<input class="form-control" type="text" name="last_name" id="last_name" value="<?php echo $lastname; ?>" />
			</div>
			<div class="col-xs-12 col-sm-6">
				<label for="first_name">First Name:</label>
				<div>
					<input class="form-control" type="text" name="first_name" id="first_name" value="<?php echo $firstname; ?>" />
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<label for="middle_name">Middle Name:</label>
				<div>
					<input class="form-control" type="text" name="middle_name" id="middle_name" value="<?php echo $middlename; ?>" />
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<label for="username">Username:</label>
				<div>
					<input class="form-control" type="text" name="username" id="username" value="<?php echo $username; ?>" />
					</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<label for="email">Email:</label>
				<div>
					<input class="form-control" type="text" name="email" id="email" value="<?php echo $email; ?>"  />
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<label  for="role">User Location:</label>
				<div>
					<select class="form-control" name="user_location" id="user_location">
						<option></option>
						<option <?php echo ($user_location=="Tayud" ? "SELECTED" : ""); ?> value="Tayud">Tayud</option>
						<option <?php echo ($user_location=="NRA" ? "SELECTED" : ""); ?> value="NRA">NRA (Cebu)</option>
						<option <?php echo ($user_location=="Makati" ? "SELECTED" : ""); ?> value="Makati">Makati</option>
					</select>
				</div>
			</div>
            <div class="col-xs-12 col-sm-6">
				<label  for="role">Role:</label>
				<div>
					<select class="form-control" name="role" id="role">
						<option></option>
						<option <?php echo ($role=="Administrator" ? "SELECTED" : ""); ?> value="Administrator">Administrator</option>
						<option <?php echo ($role=="Human Resources" ? "SELECTED" : ""); ?> value="Human Resources">Human Resources</option>
						<option <?php echo ($role=="Payroll" ? "SELECTED" : ""); ?> value="Payroll">Payroll</option>
                        <option <?php echo ($role=="Accounting" ? "SELECTED" : ""); ?> value="Accounting">Accounting</option>
                        <option <?php echo ($role=="Operations" ? "SELECTED" : ""); ?> value="Operations">Operations</option>
                        <option <?php echo ($role=="Inventory" ? "SELECTED" : ""); ?> value="Inventory">Inventory</option>
                        <option <?php echo ($role=="Purchasing" ? "SELECTED" : ""); ?> value="Purchasing">Purchasing</option>
                        <option <?php echo ($role=="Monitoring" ? "SELECTED" : ""); ?> value="Monitoring">Monitoring</option>
                        <option <?php echo ($role=="Finance" ? "SELECTED" : ""); ?> value="Finance">Finance</option>
                        <option <?php echo ($role=="Asset Management" ? "SELECTED" : ""); ?> value="Asset Management">Asset Management</option>
                        <option <?php echo ($role=="Compliance" ? "SELECTED" : ""); ?> value="Compliance">Compliance</option>
                        <option <?=$role == "ESS" ? "SELECTED" : ""?> value="ESS">ESS</option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<label>Employee:</label>
				<input type="text" name="employee" class="form-control" value="<?=$employee?>" id="emp_auto_complete">
			</div>
			<div class="col-xs-12 col-sm-12">
				<label for="signature">Signature:</label>
				<input class="center-block" type="file" name="picture" id="picture">
				<?php echo $signature; ?>
			</div>
		</div>
	</div>
	<div class='col-xs-12 col-xs-12 col-lg-12'>
		<span class="pull-right">
			<input type='button' value='Save' name='btnSubmit' id="submitbtn" class='btn btn-success btn-m' onclick='javascript: checkform()'/>
			<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
		</span>
		<br><br><br>
	</div>
</form>
<script>
function validateEmail(email) {
	var re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
	}
	function validateRadio (radios)	{
		for (var i = 0; i < radios.length; i++)	{
			if (radios[i].checked) {return true;}
		}
		return false;
	}
function checkform() {

	var msg="";
	var patt1=/^[0-9]+$/i;
	// /*
	var last_name=document.getElementById("last_name").value;
	if (last_name==null || last_name=="") {
		msg+="Last Name is required! <br/>";
	}
	var first_name=document.getElementById("first_name").value;
	if (first_name==null || first_name=="") {
		msg+="First Name is required! <br/>";
	}
	var username=document.getElementById("username").value;
	if (username==null || username=="") {
		msg+="Username is required! <br/>";
	}
	var email=document.getElementById("email").value;
	if (email==null || email=="") {
		msg+="Email is required! <br/>";
	}
	else if (validateEmail(email)==false) {
		msg+="Email is not valid! <br/>";
	}
	var role=document.getElementById("role").selectedIndex;
	if (role==null || role=="") {
		msg+="Role is required! <br/>";
	}
	if(msg!="") {
		toastr['error'](msg,"ABAS says");
		return false;
	}
	else {
		document.getElementById("employee_info").submit();
		return true;
	}

}

$( "#emp_auto_complete" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>users/autocomplete_employee",
	minLength: 2,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		if (ui.content.length === 0) {
			toastr.clear();
			toastr["warning"]("Employee not found on that company!", "ABAS Says");
		}
		else {
			toastr.clear();
		}
	},
	select: function( event, ui ) {
		$('#emp_auto_complete').val( ui.item.label );
		return false;
	}
});


</script>
