<style>
/*
.data-table { background-color:#000; margin:10px; border:0px; }
.data-table th { background-color:#500; color:#FFF; padding:5px; }
.data-table td { background-color:#333; color:#FFF; padding:5px; }
.data-table td a { font-size:10px; }
a { text-decoration:none; color:#F00; }
input { margin:5px; text-align:center; }
input[type=text] { background-color:#000; color:#FFF; }
input[type=password] { background-color:#000; color:#FFF; }
input[type=email] { background-color:#000; color:#FFF; }
input[type=file] {
	background-color:#000;
	color:#FFF;
	border-radius:10px;
	cursor:pointer;
	padding:5px 10px 5px 10px;
	border:1px solid #800;
}
input[type=file]:hover { border:1px solid #F00; }
select { background-color:#000; color:#FFF; }
select:first-child { color:#888; }
textarea { background-color:#000; color:#FFF; }

.btnSubmit {
	background-color:#D44;
	color:#FFF;
	padding:10px;
	border-radius:10px;
	max-width:200px;
	min-width:50px;
	cursor:pointer;
}
.input-form table{
	margin:50px auto;
	text-align:center;
}
*/
</style>
<?php

foreach($tablefields AS $tf) {
	$record[$tf->COLUMN_NAME]	=	"";
}

$title			=	"Add New Record to ".$table;
$formaction		=	HTTP_PATH.'home/encode/'.$table.'/insert';
if(isset($recid)) {
	$title		=	"Edit Record In ".$table;
	$record		=	$this->db->query("SELECT * FROM ".$table." WHERE id=".$recid);
	$record		=	$record->result_array();
	$record		=	$record[0];
	// echo "<pre>";print_r($record);echo "</pre>";
	$r			=	$record;
	$formaction		=	HTTP_PATH.'home/encode/'.$table.'/update/'.$r['id'];
}

foreach($tablefields AS $tf) {
	// echo "<pre>";print_r($tf);echo "</pre>";
	$validation			=	"";
	$datatype			=	"text";
	if($tf->IS_NULLABLE=="NO" && $tf->COLUMN_KEY!="PRI") {
		$validation		=	"str";
	}
	if($tf->DATA_TYPE=="int" || $tf->DATA_TYPE=="bigint") {
		$validation		=	"intopt";
	}
	if($tf->DATA_TYPE=="datetime" || $tf->DATA_TYPE=="date") {
		$datatype		=	"date";
	}
	// if($tf->COLUMN_NAME=="id" || $tf->COLUMN_NAME=="stat") {}else{
	if($tf->COLUMN_NAME=="id" || (isset($recid) && $tf->COLUMN_NAME=="created") || (isset($recid) && $tf->COLUMN_NAME=="created_by")) {}else{
		$value	=	$record[$tf->COLUMN_NAME];
		if($tf->COLUMN_NAME=="stat") { $value=1; }
		if($tf->COLUMN_NAME=="created") { $value=date("Y-m-d H:i:s"); }
		if($tf->COLUMN_NAME=="created_by") { $value=$_SESSION['abas_login']['userid']; }
		$fields[]			=	array(
			"caption"=>ucwords(str_replace("_"," ",$tf->COLUMN_NAME)),
			"name"=>$tf->COLUMN_NAME,
			"datatype"=>$datatype,
			"validation"=>$validation,
			"value"=>$value
		);
	}
}
echo $this->Mmm->createInput2($formaction,$title,$fields, "primary");


?>


