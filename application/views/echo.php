<?php
$rand1	=	rand(200, 800);
$rand2	=	rand(200, 800);
$pic	=	"<img src='http://placekitten.com/".$rand1."/".$rand2."' class='center' width='".$rand1."' height='".$rand2."' />";
echo isset($disp)?$disp:$pic;
?>