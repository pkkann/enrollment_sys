<?php
	
function show_enroll_resident($id, $justcreated = false) {
	$objResponse = new xajaxResponse();

	global $dba;
	$sql = "CALL enrollments_residents_insert(".$id.", ".$_SESSION['user']['id'].", ".$_SESSION['shift']['id'].")";
	$stmt = $dba->query($sql);

	if(!$stmt) {
		$objResponse->script('swal("Hov!", "Der skete sku en fejl.. Beboeren blev ikke indskrevet :( Kontakt en administrator", "error")');
	} else {
		$objResponse->script('$(\'#modal\').modal(\'hide\');');
		if($justcreated) {
			$objResponse->script('swal("Yay!", "Beboeren blev oprettet og indskrevet", "success")');
		} else {
			$objResponse->script('swal("Yay!", "Beboeren blev indskrevet", "success")');
		}
		$objResponse->call('xajax_do_reload_shift');
	}

	return $objResponse;
}
?>