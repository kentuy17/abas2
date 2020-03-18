<?php
	$link			=	HTTP_PATH."home/forgot";
	$title			=	"Password Reset";
	$fields[]		=	array("caption"=>"Enter your Username:", "name"=>"username", "datatype"=>"text", "validation"=>"str", "value"=>"");
	$disp			=	$this->Mmm->createInput($link,$title,$fields);
	echo $disp;
?>


