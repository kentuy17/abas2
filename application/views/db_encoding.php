<h4>Database Encoding</h4>
<?php
if(!isset($_SESSION['abas_login'])) {
	header("location:http://abas.avegabros.org");
	die("<script>window.location='http://abas.avegabros.org'</script>");
}

require_once "globals.php";
global $db;
$db		=	new mysqli(DBHOST,DBUSER,DBPASS,DBNAME);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$table	=	quickquery("SHOW TABLES");
foreach($table as $t) {
	$tablename	=	$t['Tables_in_'.DBNAME];
	$structure	=	quickquery("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$tablename."' AND TABLE_SCHEMA='".DBNAME."'");
	$tbl		=	"<table border=1 class='table table-bordered table-striped table-hover'><tr><th colspan=99 id='{$tablename}' class='dialog'><a href='".HTTP_PATH."home/encode/".$tablename."'>".$tablename."</a></th></tr>";
	$tbl		.=	"<tr><th>Column Name</th><th>Data Type</th></tr>";
	foreach($structure as $s) {
		$bgcolor	=	"#FFF";
		if($s['COLUMN_KEY']=="PRI") $bgcolor="#FF0";
		$colname	=	$s['COLUMN_NAME'];
		$datatype	=	$s['DATA_TYPE'];
		$maxlength	=	$s['CHARACTER_MAXIMUM_LENGTH'];
		$tbl		.=	"<tr><td style='background-color:$bgcolor;'>{$colname}</td><td style='background-color:$bgcolor;'>{$datatype} ({$maxlength})</td></tr>";
	}
	$count		=	quickquery("SELECT COUNT(id) AS cnt FROM ".$tablename);
	$tbl		.=	"<tr><td colspan=99>".$count[0]['cnt']." rows in table</td></tr>";
	$tbl		.=	"</table>";
	echo "<div style='float:left; margin:10px;'>".$tbl."</div>";
}

function quickquery($sql) {
	global $db;
	$result = $db->query($sql);
	if($db->query($sql)) {
		if($result->num_rows > 0) {
			$data = array();
			while($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			$result->free();
			return $data;
		}
		else{
			return false;
		}
	}
	else {
		return false;
	}
}
?>