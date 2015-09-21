<?php

function initEnvironment() {
	error_reporting(0);
	if(!isset($_SESSION['curPage'])) {
		$_SESSION['curPage'] = "1";
	}
	if(!isset($_SESSION['shift'])) {
		$_SESSION['shift'] = array();
		$_SESSION['shift']['id'] = 0;
		$_SESSION['shift']['started'] = '';
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
	//Site
	$xajax->register(XAJAX_FUNCTION, 'load_main');
	$xajax->register(XAJAX_FUNCTION, 'load_nav');
	$xajax->register(XAJAX_FUNCTION, 'load_footer');
	$xajax->register(XAJAX_FUNCTION, 'load_login');
	$xajax->register(XAJAX_FUNCTION, 'login');
	$xajax->register(XAJAX_FUNCTION, 'logout');
	$xajax->register(XAJAX_FUNCTION, 'show_alert');
	$xajax->register(XAJAX_FUNCTION, 'relogin');
	
	//Shift
	$xajax->register(XAJAX_FUNCTION, 'show_new_shift');
	$xajax->register(XAJAX_FUNCTION, 'show_close_shift');
	$xajax->register(XAJAX_FUNCTION, 'create_shift');
	$xajax->register(XAJAX_FUNCTION, 'close_shift');
	$xajax->register(XAJAX_FUNCTION, 'do_reload_shift');
	
	//Resident
	$xajax->register(XAJAX_FUNCTION, 'show_enroll_resident');
	$xajax->register(XAJAX_FUNCTION, 'search_resident');
	$xajax->register(XAJAX_FUNCTION, 'show_edit_resident');
	$xajax->register(XAJAX_FUNCTION, 'save_resident');
	$xajax->register(XAJAX_FUNCTION, 'create_resident');
	$xajax->register(XAJAX_FUNCTION, 'delete_resident');
	$xajax->register(XAJAX_FUNCTION, 'show_resident_details');
	$xajax->register(XAJAX_FUNCTION, 'show_new_resident');
	$xajax->register(XAJAX_FUNCTION, 'load_residents');

	//Guest
	$xajax->register(XAJAX_FUNCTION, 'show_enroll_guest');
	$xajax->register(XAJAX_FUNCTION, 'enroll_guest');
	$xajax->register(XAJAX_FUNCTION, 'load_guests');
	$xajax->register(XAJAX_FUNCTION, 'search_guest');
	$xajax->register(XAJAX_FUNCTION, 'show_guest_details');
	$xajax->register(XAJAX_FUNCTION, 'show_new_guest');
	$xajax->register(XAJAX_FUNCTION, 'create_guest');
	$xajax->register(XAJAX_FUNCTION, 'delete_guest');
	$xajax->register(XAJAX_FUNCTION, 'save_guest');
	$xajax->register(XAJAX_FUNCTION, 'show_edit_guest');

	//User
	$xajax->register(XAJAX_FUNCTION, 'load_users');
	$xajax->register(XAJAX_FUNCTION, 'search_user');
	$xajax->register(XAJAX_FUNCTION, 'show_user_details');
	$xajax->register(XAJAX_FUNCTION, 'show_new_user');
	$xajax->register(XAJAX_FUNCTION, 'show_edit_user');
	$xajax->register(XAJAX_FUNCTION, 'create_user');
	$xajax->register(XAJAX_FUNCTION, 'delete_user');
	$xajax->register(XAJAX_FUNCTION, 'save_user');
	$xajax->register(XAJAX_FUNCTION, 'show_setPwd_user');

	//Profile
	$xajax->register(XAJAX_FUNCTION, 'load_profile');
	$xajax->register(XAJAX_FUNCTION, 'show_profile');
	$xajax->register(XAJAX_FUNCTION, 'save_profile');
	$xajax->register(XAJAX_FUNCTION, 'show_setPwd_profile');
	$xajax->register(XAJAX_FUNCTION, 'save_pwd');

	//Shift
	$xajax->register(XAJAX_FUNCTION, 'load_shifts');
	$xajax->register(XAJAX_FUNCTION, 'show_shift_details');
}
	
?>