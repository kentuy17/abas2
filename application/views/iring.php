<?php
$rand1	=	rand(200, 600);
$rand2	=	rand(200, 600);
// $imgsrc	=	LINK."assets/images/important_img_do_not_delete.jpg"; // Mr Bean
$imgsrc	=	HTTP_PATH.'../assets/images/iring.jpg'; // Random Cat
$imgdim	=	" width='450' height='400'";
$pic	=	"<div style='margin:0px auto; text-align:center; padding:20px;'>
	<h2>You are prohibited from accessing that page!</h2>
	<br/><br/><img src='".$imgsrc."' class='center' ".$imgdim." /></div>";
echo isset($disp)?$disp:$pic;
?>