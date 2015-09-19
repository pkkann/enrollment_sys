<?php

function load_footer() {
	$objResponse = new xajaxResponse();
	$text .= gen_footer();
	$objResponse->assign("bottom_wrapper", "innerHTML", $text);
	return $objResponse;
}

function gen_footer() {
	if($_SESSION['shift']['id'] != 0) {
		global $dba;
		$sql = "SELECT id FROM enrollments_residents WHERE shift = " . $_SESSION['shift']['id'];
		$stmt = $dba->query($sql);
		$residents = $stmt->rowCount();
		$sql = "SELECT id FROM enrollments_guests WHERE shift = " . $_SESSION['shift']['id'];
		$stmt = $dba->query($sql);
		$guests = $stmt->rowCount();
	}

	$text = '';
	$text .= '<nav class="navbar navbar-default navbar-fixed-bottom">';
		$text .= '<div class="container-fluid">';
			if($_SESSION['shift']['id'] == 0) {
				$text .= '<p class="navbar-text animated flash"><i class="fa fa-square-o"></i> - Ingen vagt startet, indskrivning slået fra</p>';
			} else {
				$text .= '<p class="navbar-text animated flash"><i class="fa fa-check-square-o"></i> - startet '.$_SESSION['shift']['started'].'</p>';

				$text .= '<p class="navbar-text">Beboere indskrevet: '.$residents.'</p>';
				$text .= '<p class="navbar-text">Gæster indskrevet: '.$guests.'</p>';
			}
			$text .= '<div class="navbar-right">';
				$text .= '<p class="navbar-text"> v1.0 Beta</p>';
				$text .= '<p class="navbar-text">Logged ind som '.$_SESSION['user']['name'].'</p>';
			$text .= '</div>';
		$text .= '</div>';
	$text .= '</nav>';
	return $text;
}

?>