<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

##########################################################################
##########################################################################
#######################                         ##########################
#######################  CREATED BY MARK MASKE  ##########################
#######################  	admin@mmmaske.com	##########################
#######################           -             ##########################
#######################      MARCH 2014         ##########################
#######################           -             ##########################
#######################    CUSTOM FUNCTIONS     ##########################
#######################                         ##########################
##########################################################################
##########################################################################


class Mmm extends CI_Model{
	public function __construct() {
		// $this->load->database();
	}
	function sanitize($string) {
		// $string =str_replace(';','',$string);
		// $string =str_replace('"','&quot;',$string);
		// $string =str_replace('&','&amp;',$string);
		// $string =str_replace('>','&lt;',$string);
		// $string =str_replace('<','&gt;',$string);
		//$string =str_replace('/','',$string);
		// $string =str_replace('--','-',$string);
		$string =str_replace('<script','<!--',$string);
		$string =str_replace('</script>','-->',$string);
		$string =addslashes($string);
		$string =trim($string);
		$string =preg_replace('/\s+/', ' ',$string);
		// $string =htmlspecialchars($string);
		//$this->db->escape($string);
		//$this->db->escape_str($string);
		//$this->db->escape_like_str($string);
		return $string;
	}
	function userTracking() { //if 1st time to view page, return TRUE; if !1st time to view page, return FALSE;
		$unique	= md5($_SERVER['REMOTE_ADDR'].date("d-m-Y"));
		$page	= $this->sanitize(SITEURL.$_SERVER['REQUEST_URI']);
		$_SESSION['unique'] = $unique;

		$check = $this->db->query("SELECT * FROM pageviews WHERE ip='".$_SERVER['REMOTE_ADDR']."' AND session='".$_SESSION['unique']."' AND page='".$page."'");
		$check = $check->result_array();
		if(count($check)==0) {
			$user="";
			if(isset($_SESSION['login']['uname'])) {
				if($_SESSION['login']['uname']!="") { $user=$_SESSION['login']['uname']; }
			}
			// echo "insert in db";
			$this->db->query("INSERT INTO pageviews (ip,session,page,user,tdate)VALUES('".$_SERVER['REMOTE_ADDR']."','".$_SESSION['unique']."','".SITEURL.$_SERVER['REQUEST_URI']."','".$user."','".date("Y-m-d H:i")."')");
			return TRUE;
		}
		else {
			// echo "record exists";
			return FALSE;
		}
	}
	function getAge($defaultDate) { //returns array['y'],['m'],['d'] for years,months,days
		$bday = new DateTime(date("Y-m-d",strtotime($defaultDate)));
		$today = new DateTime('00:00:00'); // use this for the current date
		//$today = new DateTime('2010-08-01 00:00:00'); // for testing purposes
		$diff = $today->diff($bday);
		$yearsAlive = $diff->y;
		$monthsAlive = $diff->m;
		$daysAlive = $diff->d;
		$lifespan['y'] = $yearsAlive;
		$lifespan['m'] = $monthsAlive;
		$lifespan['d'] = $daysAlive;

		return $lifespan;
	}
	function query($sql, /*$type="array",*/ $action="") { //returns object of query
	/*
		MySQL table requirement:

		This function uses the following table...

		MySQL >	CREATE TABLE IF NOT EXISTS `db_activity` (`id` int(111) NOT NULL
				AUTO_INCREMENT,`ip` varchar(100) NOT NULL,`timestamp` timestamp NOT NULL DEFAULT
				CURRENT_TIMESTAMP,`query` text NOT NULL,`action` varchar(1000) DEFAULT
				NULL,`page` varchar(256) DEFAULT NULL, `referrer` varchar(256) DEFAULT NULL,
				PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1

	*/
		$debug	=	debug_backtrace();
		$file	=	$debug[0]['file'];
		$line	=	$debug[0]['line'];
		if($file==WPATH."application/models/Mmm.php") {
			$file	=	$debug[1]['file'];
			$line	=	$debug[1]['line'];
		}
		if($action=="" || $action==null || empty($action)) { die("Your query at line ".$line." in ".$file." does not have an action!"); }
		if(isset($_SESSION['abas_login']['username'])) {
			$action	=	$_SESSION['abas_login']['username'].": ".$action;
		}
		if(!isset($_SESSION['uniqid'])) { $uniqid=md5(uniqid()); }
		else { $uniqid=$_SESSION['uniqid']; }


		$source = 'Line '.$line.' in file '.$file;
		$referer= isset($_SERVER['HTTP_REFERER']) ? $this->sanitize($_SERVER['HTTP_REFERER']):"" ;
		$tracker = '
			INSERT INTO db_activity (
				ip,
				session_id,
				timestamp,
				query,
				action,
				page,
				referrer,
				source
			)
			VALUES (
				"'.$_SERVER['REMOTE_ADDR'].'",
				"'.$uniqid.'",
				"'.date("Y-m-d H:i:s").'",
				"'.$this->sanitize($sql).'",
				"'.$this->sanitize($action).'",
				"'.$this->sanitize($_SERVER['REQUEST_URI']).'",
				"'.$referer.'",
				"'.$source.'"
			)
		';
		$q = $this->db->query($tracker);
		$response = $this->db->query($sql);
		return $response;
	}
	function datediff($interval, $datefrom, $dateto, $using_timestamps = false) { //to-do
	/*
	$interval can be:
	yyyy - Number of full years
	q - Number of full quarters
	m - Number of full months
	y - Difference between day numbers
	(eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
	d - Number of full days
	w - Number of full weekdays
	ww - Number of full weeks
	h - Number of full hours
	n - Number of full minutes
	s - Number of full seconds (default)
	*/
	if (!$using_timestamps) {
	$datefrom = strtotime($datefrom, 0);
	$dateto = strtotime($dateto, 0);
	}
	$difference = $dateto - $datefrom; // Difference in seconds
	switch($interval) {
	case 'yyyy': // Number of full years
	$years_difference = floor($difference / 31536000);
	if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
	$years_difference--;
	}
	if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
	$years_difference++;
	}
	$datediff = $years_difference;
	break;
	case "q": // Number of full quarters
	$quarters_difference = floor($difference / 8035200);
	while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
	$months_difference++;
	}
	$quarters_difference--;
	$datediff = $quarters_difference;
	break;
	case "m": // Number of full months
	$months_difference = floor($difference / 2678400);
	while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
	$months_difference++;
	}
	$months_difference--;
	$datediff = $months_difference;
	break;
	case 'y': // Difference between day numbers
	$datediff = date("z", $dateto) - date("z", $datefrom);
	break;
	case "d": // Number of full days
	$datediff = floor($difference / 86400);
	break;
	case "w": // Number of full weekdays
	$days_difference = floor($difference / 86400);
	$weeks_difference = floor($days_difference / 7); // Complete weeks
	$first_day = date("w", $datefrom);
	$days_remainder = floor($days_difference % 7);
	$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
	if ($odd_days > 7) { // Sunday
	$days_remainder--;
	}
	if ($odd_days > 6) { // Saturday
	$days_remainder--;
	}
	$datediff = ($weeks_difference * 5) + $days_remainder;
	break;
	case "ww": // Number of full weeks
	$datediff = floor($difference / 604800);
	break;
	case "h": // Number of full hours
	$datediff = floor($difference / 3600);
	break;
	case "n": // Number of full minutes
	$datediff = floor($difference / 60);
	break;
	default: // Number of full seconds (default)
	$datediff = $difference;
	break;
	}
	return $datediff;
	}
	function calcWeeks($week1, $week2) { //week 1 minus week 2
		$diff = "";
		// echo $week1 . " " . $week2;
		$first = date("d m Y", $week1);
		$second = date("d m Y", $week2);
		$w1 = explode(" ", $first);
		$w2 = explode(" ", $second);
		print_r($w1);
		print_r($w2);

		return $diff;
	}
	function displayTable($table, $where="", $function="", $exceptions=array(), $inner=array(), $innerID="") { //returns HTML formatted database table
	/*
	 * $table - the table where the data is taken from
	 * $where - MySQL parameter to select specific entries
	 * $function - link for the function used
	 * $exceptions - if the field exists in the array, it will not be displayed
	 *
	 */

		$qres	=$this->db->query("SELECT * FROM $table $where");
		$qcols	=$this->db->query("SHOW COLUMNS FROM $table");
		$res	= $qres->result_array()==true ? $qres->result_array() : "";
		$cols	= $qcols->result_array()==true ? $qcols->result_array() : "";

		$tbl	="<table class='data-table center' cellpadding=5>";
		$tbl	.="<tr><td colspan='".count($cols)."'><a href='".LINK."".$function."/". (($innerID!="")?$innerID."/":"") ."add'>[+] Add New</a></td></tr>";
		$tbl	.="<tr>";
		foreach($cols as $c) {
			$display=TRUE;
			if(in_array($c['Field'],$exceptions)){ $display=FALSE; }
			if($display==TRUE) {
				$tbl	.= "<th>".ucfirst(str_replace("_"," ",$c['Field']))."</th>";
			}
		}
		$tbl	.= ($function!="")?"<th>Manage</th>":""; //if function isset, display management
		$tbl	.="</tr>";
		foreach($res as $f) {
			$tbl	.= "<tr>";
			foreach($cols as $c) {
				$display=TRUE;
				if(in_array($c['Field'],$exceptions)){ $display=FALSE; }
				if($display==TRUE) {
					$content=	strip_tags($f[$c['Field']]);
					$content=	(strlen($content) > 250)?substr($content,0,150)."...":$content;
					$tbl	.= "<td>".$content."</td>";
				}

				if($c['Field']=="id") {
					$currentID=$f[$c['Field']];
				}
			}
			$tbl	.= ($function!="")?"<td><a href='".LINK."".$function."/". (($innerID!="")?$innerID."/":"") ."edit/".$currentID."'>[&Delta;] Edit</a><hr/><a href='".LINK."".$function."/". (($innerID!="")?$innerID."/":"") ."delete/".$currentID."'>[&Omega;] Delete</a>":""; //if function isset, display management
			if($inner==="") { $inner=array(); }
			foreach($inner as $i) {
				$tbl	.=	($i!="")?"<hr/><a href='".LINK."admin/".$function."/".$i."/".$f['id']."' />".ucfirst($i)."</a>":"";
			}
			$tbl	.= "</tr>";
		}
		$tbl	.= "</table>";
		return $tbl;
	}
	function getCountry($IP="") { //doesnt work????
			$searchthis	=	$IP=="" ? $_SERVER['REMOTE_ADDR'] : $IP;
			$json		=	file_get_contents("http://freegeoip.net/json/".$searchthis);
			$data		=	json_decode($json);
			return $data;
		}
	function createForm($action, $title, $fields) {//DEPRECIATED
	/*
	 *
	 *	Dependent on jQuery, jQueryUI and CKEditor
	 *
	 *	$action - the URL where the form will lead
	 *	$title - displayed as table header
	 *	$fields - array(caption, input name, datatype[text/area/email/password/date/custom], validation[int/str/eml/dte], value)
	 *		* - if custom, be sure to use the name/id parameter as the function name + array index
	 *	longhand of fields - array("caption"=>"", "name"=>"", "datatype"=>"", "validation"=>"", "value"=>"");
	 *	shorthands of fields - array("","","","","");
	 *
	 */

		exit("Can't see your form? Good! Function createForm is depreciated, please use createFormV2 instead!");
		//print_r($fields); echo "<hr/>";
		$formid	= strtolower(str_replace(" ","_",$this->sanitize($title)));
		$tbl	= "<div class='input-form'>";
		$tbl	.= "<form action='$action' method='POST' id='$formid' onsubmit='javascript: checkform()' enctype='multipart/form-data'>";
		$tbl	.=	(CSRF_ENABLE==true)?$this->Mmm->createCSRF():"";
		$tbl	.="<table class='data-table' cellpadding=5>";
		$tbl	.="<tr><th colspan='2'>".$title."</th></tr>";
		$js	= "<script>";
		$js	.= '
			$(".numeric-only").keydown(function (e) {
				if (
					$.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || // Allow: backspace, delete, tab, escape, enter and .
					(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || // Allow: Ctrl+A, Command+A
					(e.keyCode >= 35 && e.keyCode <= 40) // Allow: home, end, left, right, down, up
				) {
					return;
				}
				// Ensure that it is a number and stop the keypress
				if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
					e.preventDefault();
				}
			});
			function validateEmail(email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
			}
			function validateRadio (radios)	{
				for (var i = 0; i < radios.length; i++)	{
					if (radios[i].checked) {return true;}
				}
				return false;
			}
		'; //JS validate email and radio function
		$js	.= 'function checkform() {'; // starts JS function
		$js	.= '
			var msg="";
			var patt1=/^[0-9]+$/i;
		'; //define JS variables
		$x=0;
		foreach($fields as $f) { //html table and inputs
			$f['caption']=(isset($f['caption']))?$f['caption']:$f[0];
			$f['name']=(isset($f['name']))?$f['name']:$f[1];
			$f['datatype']=(isset($f['datatype']))?$f['datatype']:$f[2];
			$f['validation']=(isset($f['validation']))?$f['validation']:$f[3];
			$f['value']=(isset($f['value']))?$f['value']:$f[4];

			if($f['datatype']=="date" || $f['datatype']=="text" || $f['datatype']=="area" || $f['datatype']=="email" || $f['datatype']=="password") {
				//print_r($f); echo "<hr/>";
				### HTML
				$tbl	.="<tr>";
				$f['name']=str_replace(" ", "_", $f['name']);
				extract($f);
				// $value	= mysql_real_escape_string(addslashes($value));
				$datebutton	= "<input class='".(($validation=="i" || $validation=="int" || $validation=="integer")?'numeric-only':'')."' type='text' id='".$name.$x."' name='$name' placeholder='$caption' value='$value' />";
				$inputfield = "<input class='".(($validation=="i" || $validation=="int" || $validation=="integer")?'numeric-only':'')."' type='$datatype' id='".$name.$x."' name='$name' placeholder='$caption' value='$value' />";
				$areafield = "<textarea id='".$name.$x."' name='$name'>$value</textarea>";

				$input = ($datatype=="date")?$datebutton:$inputfield;
				$input = ($datatype=="area")?$areafield:$input;

				$tbl	.="<th>$caption</th>";
				$tbl	.= "<td style='text-align:center;'>".$input."</td>";
				if($datatype=="area") {
					$tbl	.= '<script>CKEDITOR.replace("'.$name.$x.'"); CKEDITOR.commands.save.disable();</script>';
				}
				if($validation=="d" || $validation=="dte" || $validation=="date") {
					$tbl	.= '<script>$("#'.$name.$x.'").datepicker();</script>';
				}
				$tbl	.= ($datatype=="date")?'':'';
				$tbl	.= "</tr>";
			}
			else {
				extract($f);
				$tbl	.= "<tr>";
				$tbl	.= "<th>$caption</th>";
				$tbl	.= "<td>";
				$tbl	.= $f['value'];
				$tbl	.= "</td>";
				if($validation=="d" || $validation=="dte" || $validation=="date") {
					$tbl	.= '<script>$("#'.$name.$x.'").datepicker();</script>';
				}
				$tbl	.= "</tr>";
			}

			### JS validation
			$js	.= 'var '.$name.$x.'=document.forms.'.$formid.'.'.$name.$x.'.value;';
			if($validation=="s" || $validation=="str" || $validation=="string") {
				$js	.= '
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="'.$caption.' is required! <br/>";
					}
				';
			}
			if($validation=="e" || $validation=="eml" || $validation=="email") {
				$js	.= '
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="'.$caption.' is required! <br/>";
					}
					else if (validateEmail('.$name.$x.')==false) {
						msg+="'.$caption.' is not a valid email! <br/>";
					}
				';
			}
			if($validation=="i" || $validation=="int" || $validation=="integer") {
				$js	.='
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="'.$caption.' is required! <br/>";
					}
					else if (!patt1.test('.$name.$x.')) {
						msg+="Only numbers are allowed in '.$caption.'! <br/>";
					}
				';
			}
			if($validation=="d" || $validation=="dte" || $validation=="date") {
				$js	.='
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="'.$caption.' is required!<br/>";
					}
				';
			}
			$x++;
		}
			$js	.= 'if(msg!="") { ';
				$js	.= '$( "#validation" ).html(msg); ';
				$js	.= '$( "#validation" ).dialog({modal:true,show: {effect: "fade",duration: 1000}}); ';
				$js	.= '$(".ui-dialog-titlebar").remove(); ';
				$js	.= '$("#validation").click(function () { ';
					$js	.= '$(this).dialog("close"); ';
				$js	.= '}); ';
				// if (USERBROWSER!="IE") { $js	.= '$("#validation").textEffect();'; } // dependent on textEffect jQuery library
				$js	.= 'return false; ';
			$js	.= '} ';
			$js	.= 'else { ';
				$js	.= 'document.getElementById("'.$formid.'").submit(); ';
				$js	.= 'return true; ';
			$js	.= '} ';//logic whether submit form or not
		$js	.= '}'; //ends function
		$js	.= "</script>";

		$tbl	.= "<tr><td colspan='2' style='text-align:center;'><input type='button' value='Submit' name='btnSubmit' class='btnSubmit' onclick='javascript: checkform()' /></td></tr>";
		$tbl	.= "</table>";
		$tbl	.= "</form>";


		$tbl	.= $js; //INSERTS JS INTO TABLE
		$tbl	.= "</div>";

		return $tbl;
	}
	function createFormV2($action, $title, $fields) {
	/*
	 *
	 *	Dependent on jQuery, jQueryUI and CKEditor
	 *
	 *	$action - the URL where the form will lead
	 *	$title - displayed as table header
	 *	$fields - array(caption, input name, datatype[text/select/radio/check/area/email/password/date/custom/csv], validation[int/str/eml/dte], value)
	 * 	$selected - valid only for select/check/radio datatypes. defines the selected option.
	 *		* - if custom, be sure to use the name/id parameter as the function name + array index
	 *	longhand of fields - array("caption"=>"", "name"=>"", "datatype"=>"", "validation"=>"", "value"=>"");
	 *	shorthands of fields - array("","","","","");
	 * 	For select/check/radio fields - the value is MANDATORY and contains all options each separated by a double pipe (||)
	 *
	 */
		$dateRangeMinimum	=	"-100";	//these are for the datepicker
		$dateRangeMaximum	=	"+10";	//these are for the datepicker

		//print_r($fields); echo "<hr/>";
		$cleantitle	=	str_replace(" ","_",$this->sanitize($title));
		$cleantitle	=	str_replace("-","",$this->sanitize($cleantitle));
		$formid	= strtolower($cleantitle);
		$tbl	= "<div class='input-form'>";
		$tbl	.= "<form action='$action' method='POST' id='$formid' onsubmit='javascript: checkform()' enctype='multipart/form-data'>";
		$tbl	.=	(CSRF_ENABLE==true)?$this->Mmm->createCSRF():"";
		$tbl	.="<table class='data-table' cellpadding=5>";
		$tbl	.=($title!="")?"<tr><th colspan='2'>".$title."</th></tr>":"";
		$js	= "<script>";
		$js	.= '
			$(".numeric-only").keydown(function (e) {
				if (
					$.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || // Allow: backspace, delete, tab, escape, enter and .
					(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || // Allow: Ctrl+A, Command+A
					(e.keyCode >= 35 && e.keyCode <= 40) // Allow: home, end, left, right, down, up
				) {
					return;
				}
				// Ensure that it is a number and stop the keypress
				if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
					e.preventDefault();
				}
			});
			function validateEmail(email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
			}
			function validateRadio (radios)	{
				for (var i = 0; i < radios.length; i++)	{
					if (radios[i].checked) {return true;}
				}
				return false;
			}
		'; //JS validate email and radio function
		$js	.= 'function checkform() {'; // starts JS function
		$js	.= '
			var msg="";
			var patt1=/^[0-9]+$/i;
		'; //define JS variables
		$x=0;
		foreach($fields as $f) { //html table and inputs
			/*
			$f['caption']=(isset($f['caption']))?$f['caption']:$f[0];
			$f['name']=(isset($f['name']))?$f['name']:$f[1];
			$f['datatype']=(isset($f['datatype']))?$f['datatype']:$f[2];
			$f['validation']=(isset($f['validation']))?$f['validation']:$f[3];
			$f['value']=(isset($f['value']))?$f['value']:$f[4];
			$f['selected']=(isset($f['selected']))?$f['selected']:$f[5];
			*/
			$f['name']=str_replace(" ", "_", $f['name']);
			$f['dirtycap']=$f['caption'];
			$f['cleancap']=strip_tags($f['caption']);
			$f['cleancap']= preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $f['cleancap']);
			if($f['datatype']=="date" || $f['datatype']=="text" || $f['datatype']=="area" || $f['datatype']=="email" || $f['datatype']=="password") {
				//print_r($f); echo "<hr/>";
				### HTML

				extract($f);
				// $value	= mysql_real_escape_string(addslashes($value));
				$datebutton	= "<input class='".(($validation=="i" || $validation=="int" || $validation=="integer")?'numeric-only':'')."' type='text' id='".$name.$x."' name='".$name."' placeholder='".$cleancap."' value='".$value."' />";
				$inputfield = "<input class='".(($validation=="i" || $validation=="int" || $validation=="integer")?'numeric-only':'')."' type='".$datatype."' id='".$name.$x."' name='$name' ". ($datatype=='password'?"autocomplete=off":"") ." placeholder='".$cleancap."' value='".$value."' />";
				$areafield = "<textarea id='".$name.$x."' name='".$name."'>".$value."</textarea>";

				$input = ($datatype=="date")?$datebutton:$inputfield;
				$input = ($datatype=="area")?$areafield:$input;

				$tbl	.="<tr id='".$name.$x."tr'>";
				$tbl	.="<th>$caption</th>";
				$tbl	.= "<td style='text-align:center;'>".$input."</td>";
				if($datatype=="area") {
					$tbl	.= '<script>CKEDITOR.replace("'.$name.$x.'"); CKEDITOR.commands.save.disable();</script>';
				}
				if($datatype=="d" || $datatype=="dte" || $datatype=="date") {
					$tbl	.= '<script>$("#'.$name.$x.'").datepicker({changeYear: true,yearRange: "'.$dateRangeMinimum.':'.$dateRangeMaximum.'", dateFormat: "yy-mm-dd"});</script>';
				}
				$tbl	.= ($datatype=="date")?'':'';
				$tbl	.= "</tr>";
			}
			elseif($f['datatype']=="select") {
				//print_r($f); echo __LINE__ ."<hr/>";
				extract($f);
				$select	=	"<select id='".$name.$x."' name='$name'>";
				$options=explode("||",$value);
				if($validation=="s" || $validation=="str" || $validation=="string") $select.="<option value=''>Choose One</option>";
				foreach($options as $o) {
					if($o=="") exit("There is an empty option!");
					$sel	=	($selected==$o)?"selected":"";
					$select.="<option ".$sel." value='".$o."'>".$o."</option>";
				}
				$select	.=	"</select>";
				$tbl	.=	"<tr id='".$name.$x."tr'>";
				$tbl	.=	"<th>$caption</th>";
				$tbl	.=	"<td><p style='text-align:center;'>".$select."</p></td>";
				$tbl	.=	"</tr>";
			}
			elseif($f['datatype']=="radio") {
				extract($f);
				$options=explode("||",$value);
				$input	=	"";
				foreach($options as $k=>$o) {
					if($o=="") exit("There is an empty option!");
					$chkd	=	($selected==$o)?"checked":"";
					$inputID=	$name.$x."_".$k;
					//if($o==""){echo "There is an empty option!"; exit();}
					$input	.=	"<input type='radio' $chkd name='".$name."' id='$inputID' value='".$o."' /><label for='$inputID' />$o</label>";
				}
				$tbl	.=	"<tr id='".$name.$x."tr'>";
				$tbl	.=	"<th>$caption</th>";
				$tbl	.=	"<td>".$input."</td>";
				$tbl	.=	"</tr>";
			}
			elseif($f['datatype']=="check") {
				extract($f);
				$options=explode("||",$value);
				$input	=	"";
				foreach($options as $k=>$o) {
					$inputID=	$name.$x."_".$k;
					if($o=="") exit("There is an empty option!");
					$chkd		=	"";
					if(!is_array($selected)) {
						$chkd	=	($selected==$o)?"checked":"";
					}
					else {
						if(!empty($selected)) {
							foreach($selected as $s) {
								if($s==$o) $chkd="checked";
							}
						}
					}
					$input	.=	"<input type='checkbox' $chkd name='".$name."[]' id='$inputID' value='".$o."' /><label for='$inputID' />$o</label>";
				}
				$tbl	.=	"<tr id='".$name.$x."tr'>";
				$tbl	.=	"<th>$caption</th>";
				$tbl	.=	"<td>".$input."</td>";
				$tbl	.=	"</tr>";
			}
			else {
				extract($f);
				$tbl	.= "<tr id='".$name.$x."tr'>";
				$tbl	.= "<th>$caption</th>";
				$tbl	.= "<td>";
				$tbl	.= $f['value'];
				$tbl	.= "</td>";
				if($validation=="d" || $validation=="dte" || $validation=="date") {
					$tbl	.= '<script>$("#'.$name.$x.'").datepicker();</script>';
				}
				$tbl	.= "</tr>";
			}

			### JS validation

			if($f['datatype']=="select") {
				$js	.= 'var '.$name.$x.'=document.forms.'.$formid.'.'.$name.'.selectedIndex;';
			}
			elseif($f['datatype']=="radio" || $f['datatype']=="check") {
				$js	.= 'var '.$name.$x.'=document.forms.'.$formid.'.'.$name.";";
			}
			else {
				$js	.= 'var '.$name.$x.'=document.forms.'.$formid.'.'.$name.$x.'.value;';
			}

			if($validation=="s" || $validation=="str" || $validation=="string") {
				if($datatype=="radio") {
					$js	.=	"
					//radio
					";
					$js	.= '
						if (validateRadio('.$name.$x.')!=true) {
							msg+="'.$cleancap.' is required! <br/>";
						}

					';
				}
				elseif($datatype=="check") {
					$js	.=	'
					var proceed=false;
					';
					foreach($options as $k=>$o) {
						$inputID=	$name.$x."_".$k;
						$js	.= '
							if ('.$name.$x."_".$k.'.checked==true) {
								proceed=true;
							}
						';
					}
					$js	.= '
						if(proceed==false) {
							msg+="'.$cleancap.' is required! <br/>";
						}

					';
				}
				else {
					$js	.= '
						if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$cleancap.'") {
							msg+="'.$cleancap.' is required! <br/>";
						}
					';
				}
			}
			if($validation=="e" || $validation=="eml" || $validation=="email") {
				$js	.= '
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$cleancap.'") {
						msg+="'.$cleancap.' is required! <br/>";
					}
					else if (validateEmail('.$name.$x.')==false) {
						msg+="'.$cleancap.' is not a valid email! <br/>";
					}
				';
			}
			if($validation=="i" || $validation=="int" || $validation=="integer") {
				$js	.='
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$cleancap.'") {
						msg+="'.$cleancap.' is required! <br/>";
					}
					else if (!patt1.test('.$name.$x.')) {
						msg+="Only numbers are allowed in '.$cleancap.'! <br/>";
					}
				';
			}
			if($validation=="d" || $validation=="dte" || $validation=="date") {
				$js	.='
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$cleancap.'") {
						msg+="'.$cleancap.' is required!<br/>";
					}
				';
			}

			$x++;
		}
			$js	.= 'if(msg!="") { ';
				// $js	.= '$( "body" ).append(\'<div id="validation">&nbsp;</div>\'); ';
				// $js	.= '$( "#validation" ).html(msg); ';
				// $js	.= '$( "#validation" ).dialog({modal:true,show: {effect: "fade",duration: 1000}}); ';
				// $js	.= '$(".ui-dialog-titlebar").remove(); ';
				// $js	.= '$("#validation").click(function () { ';
					// $js	.= '$(this).dialog("close"); ';
				// $js	.= '}); ';
				// if (USERBROWSER!="IE") { $js	.= '$("#validation").textEffect();'; } // dependent on textEffect jQuery library
				$js	.=	'toastr[\'warning\'](msg,"ABAS Says"); '; // dependent on jQuery toastr library
				$js	.= 'return false; ';
			$js	.= '} ';
			$js	.= 'else { ';
				$js	.= 'document.getElementById("'.$formid.'").submit(); ';
				$js	.= 'return true; ';
			$js	.= '} ';//logic whether submit form or not
		$js	.= '}'; //ends function
		$js	.= "</script>";

		$tbl	.= "<tr><td colspan='2' style='text-align:center;'><input type='button' value='Submit' name='btnSubmit' class='btnSubmit' onclick='javascript: checkform()' /></td></tr>";
		$tbl	.= "</table>";
		$tbl	.= "</form>";


		$tbl	.= $js; //INSERTS JS INTO TABLE
		$tbl	.= "</div>";

		return $tbl;
	}
	function createInput($action, $title, $fields) { // same as  createForm except it's tableless all elements are wrapped and it's designable
	/*
	 *
	 *	Dependent on jQuery, jQueryUI and CKEditor
	 *
	 *	$action - the URL where the form will lead
	 *	$title - displayed as table header
	 *	$fields - array(caption, input name, datatype[text/select/radio/check/area/email/password/date/custom/csv], validation[int/str/eml/dte], value)
	 * 	$selected - valid only for select/check/radio datatypes. defines the selected option.
	 *		* - if custom, be sure to use the name/id parameter as the function name + array index
	 *	longhand of fields - array("caption"=>"", "name"=>"", "datatype"=>"", "validation"=>"", "value"=>"");
	 *	shorthands of fields - array("","","","","");
	 * 	For select/check/radio fields - the value is MANDATORY and contains all options each separated by a double pipe (||)
	 *
	 */
		$dateRangeMinimum	=	"-100";	//these are for the datepicker
		$dateRangeMaximum	=	"+10";	//these are for the datepicker

		//print_r($fields); echo "<hr/>";
		$cleantitle	=	str_replace(" ","_",$this->sanitize($title));
		$cleantitle	=	str_replace("-","",$this->sanitize($cleantitle));
		$formid	= strtolower($cleantitle);
		// $formid	= ($title!="")?strtolower($cleantitle):"autoform".rand();
		$formid	= "autoform".rand();



		$tbl	="<div class='panel panel-primary'>
						<div class='panel-heading'>
							<div class='panel-title'>
								<text>".$title."</text>
							</div>
						</div>
				</div>";

		//$tbl	.=($title!="")?"<div class='panel-heading'>".$title."</div>":"";
		$tbl	.= "<div class='panel-body primary'>";
		$tbl	.= "<form action='$action' role='form' method='POST' id='$formid' onsubmit='javascript: checkform()' enctype='multipart/form-data'>";
		$tbl	.=	(CSRF_ENABLE==true)?$this->Mmm->createCSRF():"";

		$js	= "<script>";
		$js	.= '
			$(".numeric-only").keydown(function (e) {
				if (
					$.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || // Allow: backspace, delete, tab, escape, enter and .
					(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || // Allow: Ctrl+A, Command+A
					(e.keyCode >= 35 && e.keyCode <= 40) // Allow: home, end, left, right, down, up
				) {
					return;
				}
				// Ensure that it is a number and stop the keypress
				if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
					e.preventDefault();
				}
			});
			function validateEmail(email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
			}
			function validateRadio (radios)	{
				for (var i = 0; i < radios.length; i++)	{
					if (radios[i].checked) {return true;}
				}
				return false;
			}
		'; //JS validate email and radio function
		$js	.= 'function checkform() {'; // starts JS function
		$js	.= '
			var msg="";
			var patt1=/^[0-9]+$/i;
		'; //define JS variables
		$x=0;
		foreach($fields as $f) { //html table and inputs
			/*
			$f['caption']=(isset($f['caption']))?$f['caption']:$f[0];
			$f['name']=(isset($f['name']))?$f['name']:$f[1];
			$f['datatype']=(isset($f['datatype']))?$f['datatype']:$f[2];
			$f['validation']=(isset($f['validation']))?$f['validation']:$f[3];
			$f['value']=(isset($f['value']))?$f['value']:$f[4];
			$f['selected']=(isset($f['selected']))?$f['selected']:$f[5];
			*/
			$f['name']=str_replace(" ", "_", $f['name']);
			$f['dirtycap']=$f['caption'];
			$f['cleancap']=strip_tags($f['caption']);
			$f['cleancap']= preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $f['cleancap']);
			if($f['datatype']=="date" || $f['datatype']=="text" || $f['datatype']=="area" || $f['datatype']=="email" || $f['datatype']=="password") {
				//print_r($f); echo "<hr/>";
				### HTML

				$f['name']=str_replace(" ", "_", $f['name']);
				extract($f);
				$name		=	str_replace(" ", "_", $name);
				// $value	= mysql_real_escape_string(addslashes($value));
				$datebutton	= "<input type='text' id='".$name.$x."' name='".$name."' placeholder='".$cleancap."' class='form-control ".(($validation=="i" || $validation=="int" || $validation=="integer")?'numeric-only':'')."' value='".$value."' />";
				$inputfield = "<input type='".$datatype."' id='".$name.$x."' name='$name' ". ($datatype=='password'?"autocomplete=off":"") ." placeholder='".$cleancap."' class='form-control' value='".$value."' />";
				$areafield = "<textarea id='".$name.$x."' name='".$name."'>".$value."</textarea>";

				$input = ($datatype=="date")?$datebutton:$inputfield;
				$input = ($datatype=="area")?$areafield:$input;

				$tbl	.="<div class='form-group'><label for='".$name.$x."'>$caption</label>";
				$tbl	.= "".$input."";
				if($datatype=="area") {
					$tbl	.= '<script>CKEDITOR.replace("'.$name.$x.'"); CKEDITOR.commands.save.disable();</script>';
				}
				if($validation=="d" || $validation=="dte" || $validation=="date") {
					$tbl	.= '<script>$("#'.$name.$x.'").datepicker({changeYear: true,yearRange: "'.$dateRangeMinimum.':'.$dateRangeMaximum.'"});</script>';
				}
				$tbl	.= ($datatype=="date")?'':'';
				$tbl	.= "</div>";
			}
			elseif($f['datatype']=="select") {
				//print_r($f); echo __LINE__ ."<hr/>";
				extract($f);
				$name	=	str_replace(" ", "_", $name);
				$select	=	"<select id='".$name.$x."' name='$name' class='form-control'>";
				$options=explode("||",$value);
				if($validation=="s" || $validation=="str" || $validation=="string") $select.="<option value=''>Choose One</option>";
				foreach($options as $o) {
					if($o=="") exit("There is an empty option!");
					$sel	=	($selected==$o)?"selected":"";
					$select.="<option ".$sel." value='".$o."'>".$o."</option>";
				}
				$select	.=	"</select>";
				// $tbl	.=	"<div class='singleinput'><span class='singleinputcaption'>$caption</span>";
				$tbl	.=	"<div class='form-group'><label for='".$name.$x."'>$caption</label>";
				$tbl	.=	"<p>".$select."</p>";
				$tbl	.=	"</div>";
			}
			elseif($f['datatype']=="radio") {
				extract($f);
				$name	=	str_replace(" ", "_", $name);
				$options=explode("||",$value);
				$input	=	"";
				foreach($options as $k=>$o) {
					if($o=="") exit("There is an empty option!");
					$chkd	=	($selected==$o)?"checked":"";
					$inputID=	$name.$x."_".$k;
					//if($o==""){echo "There is an empty option!"; exit();}
					$input	.=	"<input type='radio' $chkd name='".$name."' id='$inputID' value='".$o."' /><label for='$inputID' />$o</label>";
				}
				$tbl	.=	"<div class='singleinput'>";
				$tbl	.=	"<span class='singleinputcaption'>$caption</span>";
				$tbl	.=	"<span class='singleinputfield'>".$input."</span>";
				$tbl	.=	"</div>";
			}
			elseif($f['datatype']=="check") {
				extract($f);
				$name	=	str_replace(" ", "_", $name);
				$options=explode("||",$value);
				$input	=	"";
				$chkd	=	"";
				foreach($options as $k=>$o) {
					$inputID=	$name.$x."_".$k;
					if($o=="") exit("There is an empty option!");
					if(!is_array($selected)) {
						$chkd	=	($selected==$o)?"checked":"";
					}
					else {
						if(!empty($selected)) {
							foreach($selected as $s) {
								if($s==$o) $chkd="checked";
							}
						}
					}
					$input	.=	"<input type='checkbox' ".$chkd." name='".$name."[]' id='$inputID' value='".$o."' /><label for='$inputID' />$o</label>";
				}
				$tbl	.=	"<div class='singleinput'>";
				$tbl	.=	"<span class='singleinputform'>$caption</span>";
				$tbl	.=	"<span class='singleinputfield'>".$input."</span>";
				$tbl	.=	"</div>";
			}
			else {
				extract($f);
				$name	=	str_replace(" ", "_", $name);
				$tbl	.= "<div class='singleinput'>";
				$tbl	.= "<span class='singleinputform'>$caption</span>";
				$tbl	.= "<span class='singleinputfield'>";
				$tbl	.= $f['value'];
				$tbl	.= "</span>";
				if($validation=="d" || $validation=="dte" || $validation=="date") {
					$tbl	.= '<script>$("#'.$name.$x.'").datepicker();</script>';
				}
				$tbl	.= "</div>";
			}

			### JS validation

			if($f['datatype']=="select") {
				$js	.= 'var '.$name.$x.'=document.forms.'.$formid.'.'.$name.'.selectedIndex;';
			}
			elseif($f['datatype']=="radio" || $f['datatype']=="check") {
				$js	.= 'var '.$name.$x.'=document.forms.'.$formid.'.'.$name.";";
			}
			else {
				$js	.= 'var '.$name.$x.'=document.forms.'.$formid.'.'.$name.$x.'.value;';
			}

			if($validation=="s" || $validation=="str" || $validation=="string") {
				if($datatype=="radio") {
					$js	.=	"
					//radio
					";
					$js	.= '
						if (validateRadio('.$name.$x.')!=true) {
							msg+="'.$cleancap.' is required! <br/>";
						}

					';
				}
				elseif($datatype=="check") {
					$js	.=	'
					var proceed=false;
					';
					foreach($options as $k=>$o) {
						$inputID=	$name.$x."_".$k;
						$js	.= '
							if ('.$name.$x."_".$k.'.checked==true) {
								proceed=true;
							}
						';
					}
					$js	.= '
						if(proceed==false) {
							msg+="'.$cleancap.' is required! <br/>";
						}

					';
				}
				else {
					$js	.= '
						if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$cleancap.'") {
							msg+="'.$cleancap.' is required! <br/>";
						}
					';
				}
			}
			if($validation=="e" || $validation=="eml" || $validation=="email") {
				$js	.= '
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="'.$cleancap.' is required! <br/>";
					}
					else if (validateEmail('.$name.$x.')==false) {
						msg+="'.$cleancap.' is not a valid email! <br/>";
					}
				';
			}
			if($validation=="i" || $validation=="int" || $validation=="integer") {
				$js	.='
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="'.$cleancap.' is required! <br/>";
					}
					else if (!patt1.test('.$name.$x.')) {
						msg+="Only numbers are allowed in '.$cleancap.'! <br/>";
					}
				';
			}
			if($validation=="io" || $validation=="intopt" || $validation=="integeropt") {
				$js	.='
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="";
					}
					else if (!patt1.test('.$name.$x.')) {
						msg+="Only numbers are allowed in '.$cleancap.'! <br/>";
					}
				';
			}
			if($validation=="d" || $validation=="dte" || $validation=="date") {
				$js	.='
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="'.$cleancap.' is required!<br/>";
					}
				';
			}

			$x++;
		}
			$js	.= 'if(msg!="") { ';
				// $js	.= '$( "body" ).append(\'<div id="validation">&nbsp;</div>\'); ';
				// $js	.= '$( "#validation" ).html(msg); ';
				// $js	.= '$( "#validation" ).dialog({modal:true,width:"auto",show: {effect: "fade",duration: 1000}}); ';
				// $js	.= '$(".ui-dialog-titlebar").remove(); ';
				// $js	.= '$("#validation").click(function () { ';
					// $js	.= '$(this).dialog("close"); ';
				// $js	.= '}); ';
				// if (USERBROWSER!="IE") { $js	.= '$("#validation").textEffect();'; } // dependent on textEffect jQuery library
				$js	.=	'toastr[\'warning\'](msg,"ABAS Says"); '; // dependent on jQuery toastr library
				$js	.= 'return false; ';
			$js	.= '} ';
			$js	.= 'else { ';
				$js	.= 'document.getElementById("'.$formid.'").submit(); ';
				$js	.= 'return true; ';
			$js	.= '} ';//logic whether submit form or not
		$js	.= '}'; //ends function
		$js	.= "</script>";

		$tbl	.= "<br><input type='button' value='Submit' name='btnSubmit' class='pull-right btn btn-success' onclick='javascript: checkform()' />";
		$tbl	.= "</form>";


		$tbl	.= $js; //INSERTS JS INTO TABLE
		$tbl	.= "</div>";

		return $tbl;
	}
	function createInput2($action, $title, $fields, $type="default") { // createInput with BootStrap! Use the array index 'class' in $fields to apply classes
	/*
	 *
	 *	Dependent on jQuery, jQueryUI and CKEditor
	 *
	 *	$action - the URL where the form will lead
	 *	$title - displayed as table header
	 *	$fields - array(caption, input name, datatype[text/select/radio/check/area/email/password/date/custom/csv], validation[int/str/eml/dte], value)
	 *  $type - BootStrap only! [default/info/primary/success/warning/danger]
	 * 	$selected - valid only for select/check/radio datatypes. defines the selected option.
	 *		* - if custom, be sure to use the name/id parameter as the function name + array index
	 *	longhand of fields - array("caption"=>"", "name"=>"", "datatype"=>"", "class"=>"", "validation"=>"", "value"=>"");
	 *	shorthands of fields - array("","","","","");
	 * 	For select/check/radio fields - the value is MANDATORY and contains all options each separated by a double pipe (||)
	 *
	 */
		$dateRangeMinimum	=	"-100";	//these are for the datepicker
		$dateRangeMaximum	=	"+10";	//these are for the datepicker

		//print_r($fields); echo "<hr/>";
		$cleantitle	=	str_replace(" ","_",$this->sanitize($title));
		$cleantitle	=	str_replace("-","",$this->sanitize($cleantitle));
		$formid	= strtolower($cleantitle);
		// $formid	= ($title!="")?strtolower($cleantitle):"autoform".rand();
		$formid	= "autoform".rand();
		$tbl	="<div class='panel panel-".$type."'>";
		$tbl	.=($title!="")?"<div class='panel-heading'>".$title."</div>":"";
		$tbl	.= "<div class='panel-body'>";
		$tbl	.= "<form action='$action' role='form' method='POST' id='$formid' onsubmit='javascript: checkform()' enctype='multipart/form-data'>";
		$tbl	.=	(CSRF_ENABLE==true)?$this->Mmm->createCSRF():"";
		$js	= "<script>";
		$js	.= '
			$(".numeric-only").keydown(function (e) {
				if (
					$.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || // Allow: backspace, delete, tab, escape, enter and .
					(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || // Allow: Ctrl+A, Command+A
					(e.keyCode >= 35 && e.keyCode <= 40) // Allow: home, end, left, right, down, up
				) {
					return;
				}
				// Ensure that it is a number and stop the keypress
				if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
					e.preventDefault();
				}
			});
			function validateEmail(email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
			}
			function validateRadio (radios)	{
				for (var i = 0; i < radios.length; i++)	{
					if (radios[i].checked) {return true;}
				}
				return false;
			}
		'; //JS validate email and radio function
		$js	.= 'function checkautoform() {'; // starts JS function
		$js	.= '
			var msg="";
			//var patt1=/^[0-9]+$/i;
			var patt1=/^\d+(\.\d+)*$/i;
		'; //define JS variables
		$x=0;
		foreach($fields as $f) { //html table and inputs
			/*
			$f['caption']=(isset($f['caption']))?$f['caption']:$f[0];
			$f['name']=(isset($f['name']))?$f['name']:$f[1];
			$f['datatype']=(isset($f['datatype']))?$f['datatype']:$f[2];
			$f['validation']=(isset($f['validation']))?$f['validation']:$f[3];
			$f['value']=(isset($f['value']))?$f['value']:$f[4];
			$f['selected']=(isset($f['selected']))?$f['selected']:$f[5];
			*/
			$f['name']=str_replace(" ", "_", $f['name']);
			$f['dirtycap']=$f['caption'];
			$f['cleancap']=strip_tags($f['caption']);
			$f['cleancap']= preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $f['cleancap']);
			$f['class']=isset($f['class'])?$f['class']:"col-xs-12 col-sm-12 col-md-6 col-lg-4";
			if($f['datatype']=="date" || $f['datatype']=="text" || $f['datatype']=="area" || $f['datatype']=="email" || $f['datatype']=="password") {
				$f['name']=str_replace(" ", "_", $f['name']);
				extract($f);
				$name		=	str_replace(" ", "_", $name);
				// $value	= mysql_real_escape_string(addslashes($value));
				$datebutton	= "<input type='text' autocomplete='off' id='".$name.$x."' name='".$name."' placeholder='".$cleancap."' class='form-control ".(($validation=="i" || $validation=="int" || $validation=="integer")?'numeric-only':'')."' value='".$value."' />";
				$inputfield = "<input type='".$datatype."' id='".$name.$x."' name='$name' ". ($datatype=='password'?"autocomplete=off":"") ." placeholder='".$cleancap."' class='form-control ".(($validation=="i" || $validation=="int" || $validation=="integer")?'numeric-only':'')."' value='".$value."' />";
				$areafield = "<textarea id='".$name.$x."' name='".$name."' class='form-control'>".$value."</textarea>";

				$input = ($datatype=="date")?$datebutton:$inputfield;
				$input = ($datatype=="area")?$areafield:$input;

				$tbl	.="<div class='".$f['class']."'><label for='".$name.$x."'>$caption</label>";
				$tbl	.= "".$input."";
				// if($datatype=="area") {
					// $tbl	.= '<script>CKEDITOR.replace("'.$name.$x.'"); CKEDITOR.commands.save.disable();</script>'; // for CKEditor ONLY
				// }
				if($validation=="d" || $validation=="dte" || $validation=="date") {
					$tbl	.= '<script>$("#'.$name.$x.'").datepicker({changeYear: true,yearRange: "'.$dateRangeMinimum.':'.$dateRangeMaximum.'"});</script>';
				}
				$tbl	.= ($datatype=="date")?'':'';
				$tbl	.= "</div>";
			}
			elseif($f['datatype']=="select") {
				//print_r($f); echo __LINE__ ."<hr/>";
				extract($f);
				$name	=	str_replace(" ", "_", $name);
				$select	=	"<select id='".$name.$x."' name='$name' class='form-control'>";
				$options=explode("||",$value);
				if($validation=="s" || $validation=="str" || $validation=="string") $select.="<option value=''>Choose One</option>";
				foreach($options as $o) {
					if($o=="") exit("There is an empty option!");
					$sel	=	($selected==$o)?"selected":"";
					$select.="<option ".$sel." value='".$o."'>".$o."</option>";
				}
				$select	.=	"</select>";
				// $tbl	.=	"<div class='singleinput'><span class='singleinputcaption'>$caption</span>";
				$tbl	.=	"<div class='".$f['class']."'><label for='".$name.$x."'>$caption</label>";
				$tbl	.=	$select;
				$tbl	.=	"</div>";
			}
			elseif($f['datatype']=="radio") {
				extract($f);
				$name	=	str_replace(" ", "_", $name);
				$options=explode("||",$value);
				$input	=	"";
				foreach($options as $k=>$o) {
					if($o=="") exit("There is an empty option!");
					$chkd	=	($selected==$o)?"checked":"";
					$inputID=	$name.$x."_".$k;
					//if($o==""){echo "There is an empty option!"; exit();}
					$input	.=	"<input type='radio' $chkd name='".$name."' id='$inputID' value='".$o."' /><label for='$inputID' />$o</label>";
				}
				$tbl	.=	"<div class='singleinput ".$f['class']."'>";
				$tbl	.=	"<span class='singleinputcaption'>$caption</span>";
				$tbl	.=	"<span class='singleinputfield'>".$input."</span>";
				$tbl	.=	"</div>";
			}
			elseif($f['datatype']=="check") {
				extract($f);
				$name	=	str_replace(" ", "_", $name);
				$options=explode("||",$value);
				$input	=	"";
				$chkd	=	"";
				foreach($options as $k=>$o) {
					$inputID=	$name.$x."_".$k;
					if($o=="") exit("There is an empty option!");
					if(!is_array($selected)) {
						$chkd	=	($selected==$o)?"checked":"";
					}
					else {
						if(!empty($selected)) {
							foreach($selected as $s) {
								if($s==$o) $chkd="checked";
							}
						}
					}
					$input	.=	"<input type='checkbox' ".$chkd." name='".$name."[]' id='$inputID' value='".$o."' /><label for='$inputID' />$o</label>";
				}
				$tbl	.=	"<div class='singleinput ".$f['class']."'>";
				$tbl	.=	"<label for='".$name.$x."'>$caption</label>";
				$tbl	.=	"<span class='singleinputfield'>".$input."</span>";
				$tbl	.=	"</div>";
			}
			else {
				extract($f);
				$name	=	str_replace(" ", "_", $name);
				$tbl	.= "<div class='singleinput ".$f['class']."'>";
				$tbl	.= "<label for='".$name.$x."'>$caption</label>";
				// $tbl	.= "<span class='singleinputfield'>";
				$tbl	.= $f['value'];
				// $tbl	.= "</span>";
				if($validation=="d" || $validation=="dte" || $validation=="date") {
					$tbl	.= '<script>$("#'.$name.$x.'").datepicker();</script>';
				}
				$tbl	.= "</div>";
			}

			### JS validation

			if($f['datatype']=="select") {
				$js	.= 'var '.$name.$x.'=document.forms.'.$formid.'.'.$name.'.selectedIndex;';
			}
			elseif($f['datatype']=="radio" || $f['datatype']=="check") {
				$js	.= 'var '.$name.$x.'=document.forms.'.$formid.'.'.$name.";";
			}
			else {
				$js	.= 'var '.$name.$x.'=document.forms.'.$formid.'.'.$name.$x.'.value;';
			}

			if($validation=="s" || $validation=="str" || $validation=="string") {
				if($datatype=="radio") {
					$js	.=	"
					//radio
					";
					$js	.= '
						if (validateRadio('.$name.$x.')!=true) {
							msg+="'.$cleancap.' is required! <br/>";
						}

					';
				}
				elseif($datatype=="check") {
					$js	.=	'
					var proceed=false;
					';
					foreach($options as $k=>$o) {
						$inputID=	$name.$x."_".$k;
						$js	.= '
							if ('.$name.$x."_".$k.'.checked==true) {
								proceed=true;
							}
						';
					}
					$js	.= '
						if(proceed==false) {
							msg+="'.$cleancap.' is required! <br/>";
						}

					';
				}
				else {
					$js	.= '
						if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$cleancap.'") {
							msg+="'.$cleancap.' is required! <br/>";
						}
					';
				}
			}
			if($validation=="e" || $validation=="eml" || $validation=="email") {
				$js	.= '
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="'.$cleancap.' is required! <br/>";
					}
					else if (validateEmail('.$name.$x.')==false) {
						msg+="'.$cleancap.' is not a valid email! <br/>";
					}
				';
			}
			if($validation=="i" || $validation=="int" || $validation=="integer") {
				$js	.='
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="'.$cleancap.' is required! <br/>";
					}
					else if (!patt1.test('.$name.$x.')) {
						msg+="Only numbers are allowed in '.$cleancap.'! <br/>";
					}
				';
			}
			if($validation=="io" || $validation=="intopt" || $validation=="integeropt") {
				$js	.='
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="";
					}
					else if (!patt1.test('.$name.$x.')) {
						msg+="Only numbers are allowed in '.$cleancap.'! <br/>";
					}
				';
			}
			if($validation=="d" || $validation=="dte" || $validation=="date") {
				$js	.='
					if ('.$name.$x.'==null || '.$name.$x.'=="" || '.$name.$x.'=="'.$caption.'") {
						msg+="'.$cleancap.' is required!<br/>";
					}
				';
			}

			$x++;
		}
			$js	.= 'if(msg!="") { ';
				// $js	.= '$( "body" ).append(\'<div id="validation">&nbsp;</div>\'); ';
				// $js	.= '$( "#validation" ).html(msg); ';
				// $js	.= '$( "#validation" ).dialog({modal:true,width:"auto",show: {effect: "fade",duration: 1000}}); ';
				// $js	.= '$(".ui-dialog-titlebar").remove(); ';
				// $js	.= '$("#validation").click(function () { ';
					// $js	.= '$(this).dialog("close"); ';
				// $js	.= '}); ';
				// if (USERBROWSER!="IE") { $js	.= '$("#validation").textEffect();'; } // dependent on textEffect jQuery library
				$js	.=	'toastr[\'warning\'](msg,"ABAS Says"); '; // dependent on jQuery toastr library
				$js	.= 'return false; ';
			$js	.= '} ';
			$js	.= 'else { ';
				$js	.= 'document.getElementById("'.$formid.'").submit(); ';
				$js	.= 'return true; ';
			$js	.= '} ';//logic whether submit form or not
		$js	.= '}'; //ends function
		$js	.= "</script>";

		$tbl	.= "<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>";
		$tbl	.= "<div class='col-xs-12 col-sm-12 col-lg-12'><input type='button' value='Submit' name='btnSubmit' class='btn btn-".$type." btn-block' onclick='javascript: checkautoform()' /></div>";
		$tbl	.= "</form>";
		$tbl	.= "</div>";


		$tbl	.= $js; //INSERTS JS INTO TABLE
		$tbl	.= "</div>";

		return $tbl;
	}
	function createCSRF() {
		$n=$this->security->get_csrf_token_name();
		$h=$this->security->get_csrf_hash();
		return "<input type='hidden' name='".$n."' value='".$h."' />";
	}
	function dbInsert($table, $array, $action="") {
	/*
	 *
	 * $array - USAGE IS AS FOLLOWS: $insert['db_field'] = $value_to_be_inserted;
	 *
	 */
		$keystring	= "";
		$valstring	= "";
		foreach($array as $k=>$v) {
			$keystring	.= $k.", ";
			$valstring	.= "'".$v."', ";
		}
		$keystring = rtrim($keystring, ", ");
		$valstring = rtrim($valstring, ", ");
		$query	= "INSERT INTO $table ($keystring) VALUES ($valstring)";

		if($action=="debug") {
			$this->debug($query);die();
		}

		$this->db->trans_start();
		$this->query($query, $action);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			return FALSE;
		}else{
			return TRUE;	
		}

	}
	function multiInsert($table, $array, $action="") {
		/*
		 *
		 * $array - a 2-dimensional array as rows and columns. e.g:
		 * 	array(
		 *		"1"=> array("1st_column_name", "2nd_column_name", etc),
		 *		"2"=> array("1st_column_name", "2nd_column_name", etc)
		 *
		 *
		 */
		$keystring	=	"";
		$valstring	=	"";
		$done		=	false;
		foreach($array as $single_record) {
			$valstring	.=	"(";
			foreach($single_record as $k=>$v) {
				if($done==false) { $keystring	.= $k.", "; }
				$valstring	.= "'".$v."', ";
			}
			$done	=	true;
			$valstring = rtrim($valstring, ", ");
			$valstring	.=	"), ";
		}
		$keystring = rtrim($keystring, ", ");
		$valstring = rtrim($valstring, ", ");
		$query	= "INSERT INTO $table ($keystring) VALUES $valstring;";
		if($action=="debug") {
			$this->Mmm->debug($query);
			return false;
		}
		$this->db->trans_start();
		$this->query($query, $action);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			return FALSE;
		}else{
			return TRUE;	
		}
	}
	function dbUpdate($table, $array, $id, $action="") {
	/*
	 *
	 * $array - USAGE IS AS FOLLOWS: $update['db_field'] = $value_to_be_updated;
	 *
	 */
		$querystring	= "";
		$prev_data		=	null;
		$previous		=	$this->db->query("SELECT * FROM ".$table." WHERE id=".$id);
		if($previous!=false) {
			$prev_data	=	false;
			if($previous->row()) {
				$previous	=	$previous->result_array();
				$prev_data	=	$previous[0];
			}
		}
		// print_r($array);
		foreach($array as $k=>$v) {
			if(!empty($prev_data)) { // previous record found
				if($prev_data[$k]!=$v) { // allow only changed value in query
					$querystring.= "$k='$v', ";
				}
			}
			else { // no previous query found? power through!
				$querystring.= "$k='$v', ";
			}
		}

		if($querystring!="") {
			$querystring = rtrim($querystring, ", ");
			$query	= "UPDATE $table SET $querystring WHERE id=$id";
			if($action=="debug") {
				$this->debug($query);die();
			}
			$this->db->trans_start();
			$this->query($query, $action);
			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				return FALSE;
			}else{
				return TRUE;	
			}
		}
		else {
			return TRUE;
		}
	}
	function imgRsz($filename,$dimensions,$orig_path,$path) { //to-do

		/*
		 *	$dimensions	=	array("width"=>"","height"=>"");
		 *	$orig_path	=	includes WPATH
		 *	$path		=	includes WPATH
		 */


		include(WPATH."thumbnail.php");
		$config1 = array('type'	=> IMAGETYPE_PNG,'width'=> $dimensions['width'],'height'	=> $dimensions['height'],'method'	=> THUMBNAIL_METHOD_SCALE_MAX);
		Thumbnail::output(WPATH.$orig_path.$filename, WPATH.$path.$filename, $config1);
		$config1 = array('type'	=> IMAGETYPE_PNG,'width'=> $dimensions['width'],'height'	=> $dimensions['height'],'method'	=> THUMBNAIL_METHOD_SCALE_MIN);
		Thumbnail::output(WPATH.$orig_path.$filename, WPATH.$path.$filename, $config1);
	}
	function jsvalidate($formdata, $fields) { //to-do JS validation independent of html
		/*
		 * $formdata = array("id"=>"Form ID", );
		 *
		 *
		 * $fields = array("id"=>"Input ID", "validation"=>"Validation Type", "msg"=>"Message", "type"=>"Input Type");
		 * Input ID is the value of the id in the HTML tag
		 * Validation types are [string,email,integer,intopt(integer-optional),date]
		 * Message is the output upon error and cannot contain special characters
		 * Input Type is the HTML input used [text,select,radio]
		 */
		$js	= 'function validateEmail(email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
			}
			function validateRadio (radios)	{
				for (var i = 0; i < radios.length; i++)	{
					if (radios[i].checked) {return true;}
				}
				return false;
			}
		'; //JS validate email and radio function
		$js	.= 'function checkform() {'; // starts JS function
		$js	.= '
			var msg="";
			var patt1=/^[0-9]+$/i;
		'; //define JS variables

		foreach($fields as $f) {
			### JS validation

			if($f['type']=="select") {
				$js	.= 'var '.$f['id'].'=document.forms.'.$formid.'.'.$name.'.selectedIndex;';
			}
			elseif($f['type']=="radio" || $f['type']=="check") {
				$js	.= 'var '.$f['id'].'=document.forms.'.$formid.'.'.$name.";";
			}
			else {
				$js	.= 'var '.$f['id'].'=document.forms.'.$formid.'.'.$name.$x.'.value;';
			}

			if($validation=="s" || $validation=="str" || $validation=="string") {
				if($f['type']=="radio") {
					$js	.=	"
					//radio
					";
					$js	.= '
						if (validateRadio('.$f['id'].')!=true) {
							msg+="'.$cleancap.' is required! <br/>";
						}

					';
				}
				elseif($f['type']=="check") {
					$js	.=	'
					var proceed=false;
					';
					foreach($options as $k=>$o) {
						$inputID=	$f['id']."_".$k;
						$js	.= '
							if ('.$f['id']."_".$k.'.checked==true) {
								proceed=true;
							}
						';
					}
					$js	.= '
						if(proceed==false) {
							msg+="'.$cleancap.' is required! <br/>";
						}

					';
				}
				else {
					$js	.= '
						if ('.$f['id'].'==null || '.$f['id'].'=="" || '.$f['id'].'=="'.$cleancap.'") {
							msg+="'.$cleancap.' is required! <br/>";
						}
					';
				}
			}
			if($validation=="e" || $validation=="eml" || $validation=="email") {
				$js	.= '
					if ('.$f['id'].'==null || '.$f['id'].'=="" || '.$f['id'].'=="'.$caption.'") {
						msg+="'.$cleancap.' is required! <br/>";
					}
					else if (validateEmail('.$f['id'].')==false) {
						msg+="'.$cleancap.' is not a valid email! <br/>";
					}
				';
			}
			if($validation=="i" || $validation=="int" || $validation=="integer") {
				$js	.='
					if ('.$f['id'].'==null || '.$f['id'].'=="" || '.$f['id'].'=="'.$caption.'") {
						msg+="'.$cleancap.' is required! <br/>";
					}
					else if (!patt1.test('.$f['id'].')) {
						msg+="Only numbers are allowed in '.$cleancap.'! <br/>";
					}
				';
			}
			if($validation=="io" || $validation=="intopt" || $validation=="integeropt") {
				$js	.='
					if ('.$f['id'].'==null || '.$f['id'].'=="" || '.$f['id'].'=="'.$caption.'") {
						msg+="";
					}
					else if (!patt1.test('.$f['id'].')) {
						msg+="Only numbers are allowed in '.$cleancap.'! <br/>";
					}
				';
			}
			if($validation=="d" || $validation=="dte" || $validation=="date") {
				$js	.='
					if ('.$f['id'].'==null || '.$f['id'].'=="" || '.$f['id'].'=="'.$caption.'") {
						msg+="'.$cleancap.' is required!<br/>";
					}
				';
			}

		}

		$js	.= 'if(msg!="") { ';
				$js	.= '$( "body" ).append(\'<div id="validation">&nbsp;</div>\'); ';
				$js	.= '$( "#validation" ).html(msg); ';
				$js	.= '$( "#validation" ).dialog({modal:true,width:"auto",show: {effect: "fade",duration: 1000}}); ';
				$js	.= '$(".ui-dialog-titlebar").remove(); ';
				$js	.= '$("#validation").click(function () { ';
					$js	.= '$(this).dialog("close"); ';
				$js	.= '}); ';
				// if (USERBROWSER!="IE") { $js	.= '$("#validation").textEffect();'; } // dependent on textEffect jQuery library
				$js	.= 'return false; ';
			$js	.= '} ';
			$js	.= 'else { ';
				$js	.= 'document.getElementById("'.$formid.'").submit(); ';
				$js	.= 'return true; ';
			$js	.= '} ';//logic whether submit form or not
		$js	.= '}'; //ends function
	}
	function debug($var) {
		if(ENVIRONMENT == "development") {
			echo "<pre>";
			$debug	=	debug_backtrace();
			$file	=	$debug[0]['file'];
			$line	=	$debug[0]['line'];
			echo "Debug in ".$file." at ".$line."<br/>";
			print_r($var);
			echo "</pre>";
		}
	}
	function sendEmail($to, $subject, $msg) {
		$this->load->library('email');
		$debug_string	=	"";
		if(ENVIRONMENT == "development") {
			$debug	=	debug_backtrace();
			$file	=	$debug[0]['file'];
			$line	=	$debug[0]['line'];
			$debug_string	.= "<br/><br/><pre>";
			$debug_string	.=	"Send email in ".$file." at ".$line;
			$debug_string	.= "</pre>";
		}
		$email_config	=	array(
			'protocol'		=>	'mail',
			//'protocol'		=>	'smtp',
			//'protocol'		=>	'sendmail',
			'smtp_host'		=>	'avegabros.com',//'box299.bluehost.com',
			'smtp_port'		=>	465,
			'smtp_user'		=>	'abas@avegabros.com',
			'smtp_pass'		=>	'_4,QJ)bt-QU>LNc',
			'mailtype'		=>	'html',
			//'charset'		=>	'utf-8'
			'charset'		=>	'iso-8859-1'
		);
		$this->email->initialize($email_config);
		$this->email->set_newline("\r\n");
		$this->email->from($email_config['smtp_user']);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message("<html><body>".$msg.$debug_string."</body></html>");

		// $this->email->attach(WPATH."resumes/".$upload['client_name']);
		$this->email->send();
		if(ENVIRONMENT == "development") {
			echo "<pre>";
			echo $this->email->print_debugger();
			echo "</pre>";
		}
	}
	function numberToWords($number, $running_cnt=0) {
		/*
		 * Converts numbers to words (duh)
		 * Decimals are formatted for cheques
		 *
		 * Source: https://gist.github.com/opnchaudhary/4721977
		 *
		 * Does not work for negative numbers?
		 */
		//var_dump($number);
		if (($number < 0) || ($number > 9999999999)) {
			throw new Exception("Number is out of range");
		}
		$running_cnt++;
		// $Gn=$kn=$Hn=$Dn=$n=null;

		/* Billions */
		$Bn = floor($number / 1000000000);
		$number -= $Bn * 1000000000;

		/* Millions (giga) */
		$Gn = floor($number / 1000000);
		$number -= $Gn * 1000000;

		/* Thousands (kilo) */
		$kn = floor($number / 1000);
		$number -= $kn * 1000;

		/* Hundreds (hecto) */
		$Hn = floor($number / 100);
		$number -= $Hn * 100;

		/* Tens (deca) */
		$Dn = floor($number / 10);

		/* Ones */
		$n = $number % 10;

		$res = "";
		if ($Bn) {
			$res .= $this->numberToWords($Bn,$running_cnt) .  " Billion";
		}
		if ($Gn) {
			$res .= $this->numberToWords($Gn,$running_cnt) .  " Million";
		}
		if ($kn) {
			$res .= (empty($res) ? "" : " ") .$this->numberToWords($kn,$running_cnt) . " Thousand";
		}
		if ($Hn) {
			$res .= (empty($res) ? "" : " ") .$this->numberToWords($Hn,$running_cnt) . " Hundred";
		}
		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen");
		$tens = array("", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety");
		if ($Dn || $n) {
			if (!empty($res)) {
				$res .= " ";
				// $res .= " and "; // run this if you want separators (e.g. 'One Hundred and Twenty-Three Million Four Hundred and Fifty-Six Thousand Seven Hundred and Eigthy-Nine')
			}
			if ($Dn < 2) {
				$res .= $ones[$Dn * 10 + $n];
			} else {
				$res .= $tens[$Dn];
				if ($n) {
					$res .= "-" . $ones[$n];
				}
			}
		}
		if (empty($res)) {
			$res = "zero";
		}
		// echo $res;
		return $res;
	}
	function chequeTextFormat($number) {
		// process decimal (e.g. 1.25)
		$whole = floor($number);      // 1
		$decimal = $number - $whole; // 0.25
		if($decimal!=0) {
			$decimal = " & ".round($decimal*100)."/100";
		}
		else {
			$decimal = null;
		}
		return "***".$this->Mmm->numberToWords($number).$decimal." ONLY***";
	}

	function numberToWordsWithCents($number) {

		$whole = floor($number);      // 1
		$decimal = number_format($number - $whole,2,'.',''); // 0.25
		$number = $this->Mmm->numberToWords($number);

		if($decimal!=0) {
			$decimal = $this->Mmm->numberToWords(substr($decimal,2));
			if($number=='zero'){
				$decimal =  $decimal." Centavos only";
			}else{
				$decimal =  " and ".$decimal." Centavos only";
			}
		}
		else {
			$decimal = " only";
		}

		if($number=='zero'){
			return $decimal ;
		}else{
			return $number ." Pesos" . $decimal ;
		}

	}
}
?>
