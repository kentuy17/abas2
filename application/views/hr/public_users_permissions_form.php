<?php
	$abas_structure	=	array( // cannot contain spaces or special characters!
		"payslip"			=>	array("view"),
		"vessel_reports"	=>	array("fuel_report", "cargo_report"),
		"requisition"		=>	array("create")
	);
	$allowed_pages=array();
	$username=$lastname=$firstname=$middlename=$email=$role="";
	// echo "<pre>";print_r($user);echo "</pre>";
	if(isset($existing)) {
		// echo "<pre>";print_r($existing);echo "</pre>";
		$u				=	$user;
		foreach($existing as $e) {
			$allowed_pages[]	=	$e->permission;
		}
		$formaction	=	HTTP_PATH.'hr/public_users/update_permissions/'.$u->id;
		// echo "<pre>";print_r($allowed_pages);echo "</pre>";
	}
?>

<form class="form-horizontal" role="form" id="employee_permissions" name="employee_permissions"  action="<?php echo $formaction; ?>" method="post" enctype='multipart/form-data'>
	<?php echo $this->Mmm->createCSRF(); ?>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<span style="float:right; margin-right:10px; margin-top:-15px">
					<input class="btn btn-success force-pageload" type="submit"  value="Save" id="submitbtn" style="width:100px; margin-left:30px; margin-top:20px">
					<input class="btn btn-default"  value="Cancel" class="close" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:20px">
				</span>
				<h4>User Permissions for <?php echo isset($user->email) ? $user->email : "a user" ; ?></h4>
			</div>
		</div>
		<?php
			if(count($abas_structure)>0) :
		foreach($abas_structure as $tli=>$functions) : // $tli = top level index (module) ?>
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="heading<?php echo $tli; ?>">
				<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $tli; ?>acc" aria-expanded="false" aria-controls="<?php echo $tli; ?>acc">
					<?php echo $tli; ?>
					</a>
				</h4>
			</div>
			<div id="<?php echo $tli;?>acc" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $tli;?>">
				<div class="panel-body">
					<?php
						foreach($functions as $fi=>$f) { // $fi = function index (counter)
							$uniqid	=	$tli.'|'.$f;
							$label	=	'<label for="'.$uniqid.'">'.$f.'</label>';
							$checkbox	=	'<input type="checkbox" '.(in_array($uniqid, $allowed_pages) ? 'checked="checked"' : '').' name="'.$tli.'[]" id="'.$uniqid.'" value="'.$f.'" />';
							echo '<div class="col-lg-3 col-sm-2 col-xs-2 well text-center">'.$label.'<br/>'.$checkbox.'</div>';
						}
					?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>
</form>
