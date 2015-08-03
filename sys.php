<?php

function initEnvironment() {
	error_reporting(0);
	if(!isset($_SESSION['curPage'])) {
		$_SESSION['curPage'] = "1";
	}
}

function initXajax() {
	require_once "libraries/php/xajax/xajax.inc.php";
	global $xajax;
	$xajax = new xajax();
}

function initDB() {
	global $dba;
	$dba = new PDO("mysql:host=localhost;dbname=enrollment_sys;charset=utf8", 'root', '');
}

function registerXajaxFunctions() {
	global $xajax;
	$xajax->register(XAJAX_FUNCTION, 'load_main');
	$xajax->register(XAJAX_FUNCTION, 'load_nav');
	$xajax->register(XAJAX_FUNCTION, 'load_footer');
	$xajax->register(XAJAX_FUNCTION, 'load_login');
	$xajax->register(XAJAX_FUNCTION, 'login');
	$xajax->register(XAJAX_FUNCTION, 'logout');
	$xajax->register(XAJAX_FUNCTION, 'show_alert');
	$xajax->register(XAJAX_FUNCTION, 'load_residents');
	$xajax->register(XAJAX_FUNCTION, 'load_guests');
	$xajax->register(XAJAX_FUNCTION, 'show_resident_details');
	$xajax->register(XAJAX_FUNCTION, 'show_new_resident');
	$xajax->register(XAJAX_FUNCTION, 'create_resident');
	$xajax->register(XAJAX_FUNCTION, 'delete_resident');
	$xajax->register(XAJAX_FUNCTION, 'show_edit_resident');
	$xajax->register(XAJAX_FUNCTION, 'save_resident');
	$xajax->register(XAJAX_FUNCTION, 'search');
}
	
?>