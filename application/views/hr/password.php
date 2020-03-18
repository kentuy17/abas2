<?php
if(isset($employee_id)){
	$sql	= "SELECT * FROM public_users WHERE employee_id=".$employee_id;
	$user = $this->db->query($sql);
	$user=(array)$user->row();
	$email = $user['email'];
}
?>
<form action="<?php echo HTTP_PATH."hr/public_users/password"?>" method="POST">
<div class = "panel panel-info" >
	<div class ="form-group col-sm-6">
		<div class="form-group col-sm-6">
		<label for ="email">Your email</label>
		<input type="text" class="disable"name="email" value="<?php echo $email; ?>"/>
		</div>
		<div class = "form-group col-sm-6">
		<label for="password">Password</label>
		<input type= "text" name="password" id="password"/>
		</div>
		<div class="col-sm-6">
		<input type="submit" class="btn btn-primary" name="submit"/>
		</div>
	</div>
</div>
</form>