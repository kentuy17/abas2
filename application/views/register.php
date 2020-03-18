<div class="bg" style='background-image: url(<?php echo LINK.'assets/images/oceana.gif'; ?>);height:100%'>
	<div class="container">
		<div class="col-md-6 panel-group center-block login-form">
			<div class="panel panel-default">
				<div class="panel-heading"><h4><center><i class="glyphicon glyphicon-lock" style="font-size:35px"></i>
					<?php 
						$version = $this->Abas->readChangeLog();
						echo "<br>ABAS ".$version['num'];
					?>
				</center></h4></div>
				<div class="panel-body">
					<form action="<?=HTTP_PATH?>home/request/submit" role="form" method="POST" id="autoform130878476" onsubmit="javascript: checkform()" enctype="multipart/form-data"autocomplete="off">
						<div class="form-group col-lg-12">
							<?php echo $this->Mmm->createCSRF(); ?>
							<label for="company">Company</label>
							<select class="form-control" id="company" name="company" required>
								<option></option>
								<?php foreach ($companies as $key => $val) { ?>
									<option value="<?=$val->id?>"><?=$val->name?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group col-lg-12">
							<label for="deparment">Department</label>
							<select class="form-control" id="deparment" name="department" required>
								<option></option>
								<?php foreach ($departments as $key => $val) { ?>
									<option value="<?=$val->id?>"><?=$val->name?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group col-lg-6">
							<label for="fname">First Name</label>
							<input type="text" name="fname" class="form-control" placeholder="First Name" id="fname" required>
						</div>
						<div class="form-group col-lg-6">
							<label for="lname">Last Name</label>
							<input type="text" name="lname" class="form-control" placeholder="Last Name" id="lname" required>
						</div>
						<div class="form-group col-lg-12">
							<label for="emp_id">Employee ID</label>
							<input type="text" name="emp_id" class="form-control" placeholder="Employee ID" id="emp_id" required>
						</div>
						<div class="form-group col-lg-12">
							<label for="email">E-mail Address</label>
							<input type="email" name="email" class="form-control" placeholder="E-mail Address" id="email" required>
						</div>
						<br>
						<div class="col-xs-12 col-sm-6 col-lg-6">
							<input type="submit" value="Submit" name="btnSubmit" class="btn btn-success btn-block" onclick="javascript: checkform()"><br>
						</div>
						<div class="col-xs-12 col-sm-6 col-lg-6">
							<a href="<?=HTTP_PATH?>home/index" class="btn btn-danger btn-block">Cancel</a>
						</div>
					</form>
				</div>
				<div style='text-align:center' class='modal-footer'>
					<a class="btn btn-xs btn-dark" href="mailto:it@avegabros.org" target="_blank"><h6>Powered by AVegaIT © <?php echo date('Y');?></h6></a>
				</div>
			</div>

		</div>
	</div>
</div>

<script>
	function showPassword() {
	  var x = document.getElementById("pword1");
	  if (x.type === "password") {
	    x.type = "text";
	  } else {
	    x.type = "password";
	  }
	}
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
		var uname0=document.forms.autoform130878476.uname0.value;
		if (uname0==null || uname0=="" || uname0=="Username") {
			msg+="Username is required! <br/>";
		}
		var pword1=document.forms.autoform130878476.pword1.value;
		if (pword1==null || pword1=="" || pword1=="Password") {
			msg+="Password is required! <br/>";
		}
		if(msg!="") { toastr['warning'](msg,"ABAS Says"); return false; }
		else {
			$('body').addClass('is-loading');
			$('#modalDialog').modal('toggle');
			document.getElementById("autoform130878476").submit();
			return true;
		}
	}
</script>