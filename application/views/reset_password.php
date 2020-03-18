<?php
	$link			=	HTTP_PATH."home/reset/".$code;
	$title			=	"Password reset";
	$fields[]		=	array("caption"=>"New Password", "name"=>"password", "datatype"=>"password", "validation"=>"", "value"=>"");
	$fields[]		=	array("caption"=>"Confirm New Password", "name"=>"password2", "datatype"=>"password", "validation"=>"", "value"=>"");
	$disp			=	$this->Mmm->createInput($link,$title,$fields);

?>
<div class="container">
	<div class = "panel panel-default">
		<div class = "panel-heading">
			<h3 class = "panel-title">
				<span>
					<strong><h4>Edit Account</h4></strong>
				</span>
			</h3>
		</div>
		<div class = "panel-body">
			<?php echo $disp; ?>
		</div>
	</div>
</div>
