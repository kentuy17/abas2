<?php
if(isset($existing)) {
	$e			=	$existing;
	$username	=	$e->username;
	$lastname	=	$e->last_name;
	$firstname	=	$e->first_name;
	$middlename	=	$e->middle_name;
	$role		=	$e->role;
	$timeout		=	$e->notification_timeout/1000;
	$user_location	=	$e->user_location;
	$email		=	$e->email;
	$signature	=	LINK."assets/images/digitalsignatures/".$e->signature;
	
}
if($view_full==false){
	$title = 'Edit Account Details <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>';
	$disable = "";
}else{
	$title = "Change Password";
	$disable = "readonly";
}

$location_option = "<option value=''>Select</option>";

foreach($locations as $loc){
	$location_option .= "<option ".($loc->location_name==$user_location ? "selected":"")." value='".$loc->location_name."'>".$loc->location_name."</option>";
}
?>

<?php if($view_full==true){ ?>
	<div class = "container">
<?php } ?>
	<div class = "panel panel-primary">
		<div class = "panel-heading">
			<h3 class = "panel-title">
				<?php echo $title; ?>
			</h3>
			<?php if($view_full==false){ ?>
				</div>
			<?php } ?>
	</div>
			<div class='panel-body'>
				<form action='<?php echo HTTP_PATH; ?>home/account_details' role='form' method='POST' id='autoform377068575' onsubmit='javascript: checkform()' enctype='multipart/form-data'>
					<div class="col-xs-12 col-sm-6">
						<label for='oldpass0'>Email:*</label>
						<input type='text' id='email' name='email' autocomplete=off placeholder='Email' class='form-control' value='<?php echo $email; ?>' <?php echo $disable?>/>
					</div>
					<div class="col-xs-12 col-sm-6">
						<label  for="role">User Location:*</label>
						<select class="form-control" name="user_location" id="user_location" <?php echo $disable?>>
							<!--<option value="" selected>Select</option>
							<option <?php //echo ($user_location=="Tayud" ? "SELECTED" : ""); ?> value="Tayud">Tayud</option>
							<option <?php //echo ($user_location=="NRA" ? "SELECTED" : ""); ?> value="NRA">NRA (Cebu)</option>
							<option <?php //echo ($user_location=="Makati" ? "SELECTED" : ""); ?> value="Makati">Makati</option>-->
							<?php echo $location_option ?>
						</select>
					</div>
					<div class="col-xs-12 col-sm-6">
						<label for='oldpass0'>Old Password:</label>
						<input type='password' id='oldpass0' name='oldpass' autocomplete=off placeholder='Old Password' class='form-control' value='' />
					</div>
					<div class="col-xs-12 col-sm-6">
						<label for='password1'>New Password:</label>
						<input type='password' id='password1' name='password' autocomplete=off placeholder='New Password' class='form-control' value='' />
					</div>
					<div class="col-xs-12 col-sm-6">
						<label for='password22'>Confirm New Password:</label>
						<input type='password' id='password22' name='password2' autocomplete=off placeholder='Confirm New Password' class='form-control' value='' />
					</div>
					<div class="col-xs-12 col-sm-6">
						<label for='notification_timeout'>Notification Timeout (in seconds):*</label>
						<input type='number' id='notification_timeout' name='notification_timeout' autocomplete=off placeholder='Notification Timeout' class='form-control numeric-only' value='<?php echo $timeout; ?>' />
					</div>

					<div class="col-xs-12 col-sm-12">
						<input type="checkbox" onclick="showPassword()"> Show Password
					</div>
					<br>
					<div class="col-xs-12 col-sm-6">
						<br>
						<label>Digital Signature (Max Size: 2x2 inches)</label>
						<img class='img-responsive' src='<?php echo $signature ?>'/>
					</div>
					<div class="col-xs-12 col-sm-6">
						<br>
						<input type='file' id='digital_signature' name='digital_signature' accept="image/*"/>
					</div>
				</div>
					<div class='modal-footer'>
						<?php if($view_full==false){ ?>
							<input type="button" class="pull-right btn btn-danger btn-m" value="Discard" data-dismiss="modal">
						<?php } ?>
						<input type='button' value='Save' name='btnSubmit' class='pull-right btn btn-success btn-m' onclick='javascript: checkform()' />
					</div>
				</form>
				<script>
					function showPassword() {
					  var x = document.getElementById("oldpass0");	
					  var y = document.getElementById("password1");
					  var z = document.getElementById("password22");
					  if (x.type === "password") {
					    x.type = "text";
					  } else {
					    x.type = "password";
					  }
					  if (y.type === "password") {
					    y.type = "text";
					  } else {
					    y.type = "password";
					  }
					  if (z.type === "password") {
					    z.type = "text";
					  } else {
					    z.type = "password";
					  }
					}
				$(".numeric-only").keydown(function (e) {
				if (
						$.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || // Allow: backspace, delete, tab, escape, enter and .
						(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || // Allow: Ctrl+A, Command+A
						(e.keyCode >= 35 && e.keyCode <= 40) // Allow: home, end, left, right, down, up
					) {
					return;
					}
					// Ensure that it is a number and stop the keypress
					if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
						e.preventDefault();
					}
				});
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
					var oldpass0=document.forms.autoform377068575.oldpass0.value;
					var email=document.forms.autoform377068575.email.value;
					var user_location=document.forms.autoform377068575.user_location.value;
					//if (oldpass0==null || oldpass0=="" || oldpass0=="Old Password") {
					//	msg+="Old Password is required! <br/>";
					//}
					if(email==""){
						msg+="Email is required! <br/>";
					}
					if(user_location==""){
						msg+="User Location is required! <br/>";
					}
					if(oldpass0!=""){

						var password1=document.forms.autoform377068575.password1.value;
						if (password1==null || password1=="" || password1=="New Password") {
							msg+="New Password is required! <br/>";
						}
						var password22=document.forms.autoform377068575.password22.value;
						if (password22==null || password22=="" || password22=="Confirm New Password") {
							msg+="Confirm New Password is required! <br/>";
						}
						if(password22!=password1) {
							msg+="Your passwords do not match!<br/>";
						}
					}
					if(msg!="") {
						toastr['error'](msg,"ABAS Says");
						return false;
					}
					else {
						document.getElementById("autoform377068575").submit();
						return true;
					}
				}
				</script>
			

