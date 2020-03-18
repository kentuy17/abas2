<?php
	define('ENVIRONMENT', 'development');
	define("DBHOST"		,	"localhost");
	define("DBUSER"		,	"root");
	define("DBPASS"		,	"");
	define("DBNAME"		,	"abasv2");
	define("DS"			,	"/"); //directory separator
	define("WPATH"		,	$_SERVER['DOCUMENT_ROOT']."/abas/");
	define("SITEDIR"	,	$_SERVER['DOCUMENT_ROOT']."/");
	define("SITEURL"	,	"http://".$_SERVER['SERVER_NAME']."/abas");
	define("LINK"		,	SITEURL."/");
	define("HTTP_PATH"	,	SITEURL."/index.php/");
	define("CSRF_ENABLE",	FALSE);
	define("CSRF_TOKEN"	,	"ABAS");
	define("CSRF_COOKIE",	"ABAS");
	define("PDF_LINK"	,	"http://localhost/abas/");
	define("WPATH_UPLOAD",	$_SERVER['DOCUMENT_ROOT']."/abas/");

	//CHART OF ACCOUNTS
	define("TRADE_PAYABLE",	71);
	define("AP_CLEARING",	291);
	define("INPUT_TAX",	27);//VAT input
	define("WITHOLDING_TAX_EXPANDED",	76);
	define("MATERIALS_AND_SUPPLIES",	29);
	define("RECEIVABLES_OTHERS",	296 );
	define("PAYABLE_OTHERS",	297 );
	define("TRADE_RECEIVABLES",	10);

	$browser	=	$_SERVER['HTTP_USER_AGENT'];
	$chrome		=	'/Chrome/';
	$firefox	=	'/Firefox/';
	$ie			=	'/MSIE/';
	$using		=	"unknown";
	if (preg_match($chrome, $browser))	{ $using="Chrome/Opera"; }
	if (preg_match($firefox, $browser))	{ $using="Firefox"; }
	if (preg_match($ie, $browser))		{ $using="IE"; }

	define("USERBROWSER"	,	$using);

	define("GMAPSKEY"	,	"AIzaSyBzgtkNsY2RZ20455dHp2HdHGn-l5W4cxc");	// API key for ABAS project under mmjmaske@gmail.com

	#######################################
	#######################################
	####                                ###
	####                                ###
	####        SYSTEM SETTINGS         ###
	####                                ###
	####                                ###
	#######################################
	#######################################

	/*define("APPLY_TAXSHIELD"				,	true);
	define("MAKATI_MONTHLY_MINIMUM_WAGE"	,	502 * 26.08); // makati 12805.28
	define("CEBU_MONTHLY_MINIMUM_WAGE"		,	366 * 26.08); // cebu 10277.28
	define("TACLOBAN_MONTHLY_MINIMUM_WAGE"	,	287.57 * 26.08); // region 8 7499.83
	define("VESSEL_MONTHLY_MINIMUM_WAGE"	,	366 * 30);	// vessel 10980*/
	

	define("APPLY_TAXSHIELD"				,	true);
	define("MAKATI_MONTHLY_MINIMUM_WAGE"	,	512 * 26.08);
	define("CEBU_MONTHLY_MINIMUM_WAGE"		,	386 * 26.08);
	define("TACLOBAN_MONTHLY_MINIMUM_WAGE"	,	305 * 26.08);
	define("VESSEL_MONTHLY_MINIMUM_WAGE"	,	386 * 30);	
	define("VESSEL_MONTHLY_MINIMUM_WAGE_ADJUSTED"	,	386 * (393.50 / 12)); //adjusted based on 

	define("DEFAULT_PASSWORD"				,	md5("avegabros"));
?>