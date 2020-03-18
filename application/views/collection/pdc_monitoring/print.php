<?php 

$content = "";

if(isset($PDC)){

	foreach($PDC as $row){
		$content .= "<table><tr><td> ".$row."</td></tr></table>";
	}
	
}


$data['orientation']	=	"P";
$data['pagetype']		=	"letter";
$data['title']			=	"Post-dated Checks Monitoring";
$data['content']		=	$content;

$this->load->view('pdf-container.php',$data);

?>

