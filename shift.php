<?php

function show_new_shift() {
	$objResponse = new xajaxResponse();

	$objResponse->script('
			swal({
				title: "Ny vagt",
				text: "Vil du oprette en ny vagt?",
				type: "info",
				showCancelButton: true,
				confirmButtonClass: "btn-info",
				confirmButtonText: "Ja lav ny vagt",
				cancelButtonText: "Nej",
				closeOnConfirm: false
			},
			function(){
				xajax_create_shift();
			});');

	return $objResponse;
}

function show_close_shift() {
	$objResponse = new xajaxResponse();

	$objResponse->script('
			swal({
				title: "Afslut vagt",
				text: "Er du nu helt sikker på du vil afslutte vagten nu? Du kan IKKE fortryde!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-warning",
				confirmButtonText: "Ja afslut vagten nu",
				cancelButtonText: "Nej",
				closeOnConfirm: false
			},
			function(){
				xajax_close_shift();
			});');

	return $objResponse;
}

function create_shift() {
	$objResponse = new xajaxResponse();

	global $dba;
	$sql = "CALL shift_start(".$_SESSION['user']['id'].")";
	$stmt = $dba->query($sql);

	$objResponse->script('swal("Yay!", "Vagten blev oprettet!", "success")');
	$objResponse->call('xajax_do_reload_shift');
	return $objResponse;
}

function close_shift() {
	$objResponse = new xajaxResponse();

	if($_SESSION['shift']['id'] != 0) {
		$sql = "CALL shift_close(".$_SESSION['shift']['id'].", ".$_SESSION['user']['id'].")";
		global $dba;
		$stmt = $dba->query($sql);
		$objResponse->script('swal("Yay!", "Vagten blev afsluttet!", "success")');
		$objResponse->call('xajax_do_reload_shift');
	} else {
		$objResponse->script('swal("what?", "Der er ingen vagt started... fejl måske?", "error")');
	}

	return $objResponse;
}

function do_reload_shift() {
	$objResponse = new xajaxResponse();

	reload_shift();
	$objResponse->call('xajax_load_nav');
	$objResponse->call('xajax_load_footer');

	return $objResponse;
}

function reload_shift() {
	$_SESSION['shift']['id'] = 0;
	$_SESSION['shift']['started'] = '';
	global $dba;
	$sql = "SELECT * FROM shift_getopen";
	$stmt = $dba->query($sql);
	if($stmt->rowCount() > 0) {
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['shift']['id'] = $row['id'];
		$_SESSION['shift']['started'] = $row['started'];
	}
}

?>