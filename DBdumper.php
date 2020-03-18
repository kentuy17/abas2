<?php

#########################################################################################
#########################################################################################
####                                                                                 ####
####      MySQL Database dumper                                                      ####
####                                                                                 ####
####          Indentation is fucked up because MySQL sometimes has a hard            ####
####          time understanding chr(13), so I had to make my own line breaks        ####
####                                                                                 ####
####             B E W A R E   W H E N   E D I T I N G                               ####
####                                                                                 ####
#########################################################################################
#########################################################################################
session_start();
if(!isset($_SESSION['abas_login'])) {
	header("location:http://abas.avegabros.org");
	die("<script>window.location='http://abas.avegabros.org'</script>");
}
date_default_timezone_set("Asia/Manila");

if(file_exists('globals.php')) {
    include 'globals.php';
}
else {
	define("DBHOST","localhost");
	define("DBUSER","abas");
	define("DBPASS","");
	define("DBNAME","aabs_db");
}


$db	=	mysqli_connect(DBHOST,DBUSER,DBPASS)or die("cannot connect");
mysqli_select_db($db,DBNAME)or die("cannot find db");


$statmessage	=	"<h1>Done!</h1>";


$qtables	=	mysqli_query($db,"SHOW TABLE STATUS");
if (!$qtables) {
    die('Invalid qtables: ' . mysqli_error());
}
$header	=	"
-- Not-an-SQL-dump
-- version 1
--
-- Host: ".DBHOST."
-- Generation Time: ".date("M d, Y \a\t h:m")."

SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";
SET time_zone = \"+00:00\";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `".DBNAME."`
--

-- --------------------------------------------------------
";


//* enable for all tables == 1 file
$filename = DBNAME.'.sql';
if (!$handle = fopen($filename, 'w+')) {
	echo "Cannot open file ($filename)";
	die();
}
if (fwrite($handle, $header) === FALSE) {
	echo "Cannot write to file ($filename)";
	die();
}
//*/
$header	="";
$tables	=	"";
while($t=mysqli_fetch_array($qtables)) {
	if($t['Name']!="db_activity") {
		/* enable for 1 table == 1 file
		$filename = $t['Name'].'.sql';
		if (!$handle = fopen($filename, 'w+')) {
			echo "Cannot open file ($filename)";
			die();
		}
		if (fwrite($handle, $header) === FALSE) {
			echo "Cannot write to file ($filename)";
			die();
		}
		//*/
		$columndata		=	"";
		$insertfields	=	"";
		$insertdata		=	"";
		$qcol			=	mysqli_query($db,"SHOW columns FROM ".$t['Name']);
		$qdata			=	mysqli_query($db,"SELECT * FROM ".$t['Name']);
		$filename		=	(mysqli_num_rows($qdata)>15000)?DBNAME.'.sql':$t['Name'].".sql";
		$pri			=	"";
		while($c=mysqli_fetch_array($qcol)) {
			$insertfields	.=	"`".$c['Field']."`, ";
			$field	=	$c['Field'];
			$null	=	($c['Null']=="NO")?"NOT NULL":"";
			$extra	=	($c['Extra']=="")?"":$c['Extra'];
			if($c['Key']=="PRI")$pri=", PRIMARY KEY (`".$field."`)";
			$columndata	.=	"`".$c['Field']."` ".$c['Type']." ".$null." ".$extra.", ";
		}
		$columndata		=	rtrim($columndata,", ");
		$insertfields	=	"(".(rtrim($insertfields,", ")).")";
		$columndata		=	$columndata.$pri;

		$tables		.=	"
--
-- Table structure for table `".$t['Name']."`
--
CREATE TABLE IF NOT EXISTS `".$t['Name']."` (
	".$columndata."
) ENGINE=".$t['Engine']."  DEFAULT CHARSET=latin1 AUTO_INCREMENT=".(($t['Auto_increment']!="")?$t['Auto_increment']:0)." ;
		";

		if (fwrite($handle, $tables) === FALSE) {
			echo "Cannot write to file ($filename)";
			die();
		}
		$tables	="";
		if(mysqli_num_rows($qdata)!=0) {
			$insertdata		=	"
INSERT INTO `".$t['Name']."` ".$insertfields." VALUES ";
			$x=1;
			while($d=mysqli_fetch_array($qdata)) {
				if($x>1) { $insertdata.=","; }
				$insertdata	.=	"(";
				$qcol		=	mysqli_query($db,"SHOW columns FROM ".$t['Name']);
				while($c=mysqli_fetch_array($qcol)) {
					$insertdata	.=	'"'.addslashes($d[$c['Field']]).'",';
				}
				//$insertdata	=	rtrim($insertdata,",");
				//$insertdata	.=	"),";
				$insertdata	.=	")";

				$datalength	=	strlen($insertdata);

				$cutoff=false;
				if($datalength>30000) {$cutoff=true;$statmessage.="Cutoff ".$t['Name']." at $x with length of $datalength<br/>";}
				if($datalength>55000) {$statmessage.="Big query in ".$t['Name']." at line $x with length of $datalength<br/>";}
				if($cutoff==true) {
					//$insertquery		=	trim($insertdata,",");
					$insertquery		.=	";";
					if (fwrite($handle, $insertquery) === FALSE) {
						echo "Cannot write to file ($filename)";
						die();
					}
					$insertdata		=	"INSERT INTO `".$t['Name']."` ".$insertfields." VALUES ";
					//$x=0;
				}
				$x++;
			}
			//$insertquery		=	trim($insertdata,",");
			$insertquery		.=	";
";
			if (fwrite($handle, $insertquery) === FALSE) {
				echo "Cannot write to file ($filename)";
				die();
			}
		}
		else {$statmessage.= "<h3>Select query for ".$t['Name']." has 0 rows</h3><br/>";}

		$insertquery="";
	}
}
header("Content-Disposition: attachment; filename=\"" . basename(DBNAME.'.sql') . "\"");
header("Content-Type: application/force-download");
header("Content-Length: " . filesize(DBNAME.'.sql'));
header("Connection: close");
readfile(DBNAME.'.sql');
// echo "<html><head><style>body{background-color:#000;color:#FFF;}h1{margin:auto 0px;}</style></head><body>$statmessage</body></html>";



?>
