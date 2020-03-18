<?php
	$title		=	"Edit Password";
	$password	=	"";
	$password2	=	"";
	if(isset($existing))	{
	$e				=	$existing;
	$formaction		=	HTTP_PATH.'hr/public_users/edit/'.$e['id'];
	//$this->Mmm->debug($e);
	}
?>
<form class="form-horizontal" role="form" id="public_pass" name="public_pass"  action="<?php echo $formaction; ?>" method="post" enctype='multipart/form-data'>
	<?php echo $this->Mmm->createCSRF(); ?>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<span style="float:right; margin-right:10px; margin-top:-15px">
					<input class="btn btn-success" type="button"  value="Save" onclick="javascript:checkform()" id="submitbtn" style="width:100px; margin-left:30px; margin-top:20px">
					<input class="btn btn-default"  value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:20px">
				</span>
				<h4><?php echo $title; ?></h4>
			</div>
		</div>

		<div class="panel panel-default">
			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body" style="margin:10px;">
					<div class="form-group">
						<label for="password">New Password:</label>
						<input class="form-control" type="password" name="password" id="password" placeholder="New Password" value="<?php echo $password; ?>" />
					</div>
					<div class="form-group">
						<label for="password2">Confirm New Password:</label>
						<div>
							<input class="form-control" type="password" name="password2" id="password2" placeholder="Confirm New Password" value="<?php echo $password2; ?>" />
						</div>
					</div>
				</div>
		</div>
		</div>
		</div>
</form>

<script>
function checkform() {

	var msg="";
	var patt1=/^[0-9]+$/i;
	// /*
	var password=document.getElementById("password").value;
	if (password==null || password=="") {
		msg+="New Password is required! <br/>";
	}
	var password2=document.getElementById("password2").value;
	if (password2==null || password2=="") {
		msg+="Confirm New Password is required! <br/>";
	}
	if(msg!="") {
		toastr['error'](msg,"ABAS says");
		return false;
	}
	else {
		$('body').addClass('is-loading');
		$('#modalDialog').modal('toggle');
		document.getElementById("public_pass").submit();
		return true;
	}
}
</script>