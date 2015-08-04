<?php

function load_footer() {
	$objResponse = new xajaxResponse();
	$text .= gen_footer();
	$objResponse->assign("bottom_wrapper", "innerHTML", $text);
	return $objResponse;
}

function gen_footer() {
	$text = '';
	$text .= '<nav class="navbar navbar-default navbar-fixed-bottom">';
		$text .= '<div class="container-fluid">';
			if($_SESSION['shift']['id'] == 0) {
				$text .= '<p class="navbar-text"><i class="fa fa-square-o"></i></p>';
			} else {
				$text .= '<p class="navbar-text"><i class="fa fa-check-square-o"></i> - startet '.$_SESSION['shift']['started'].'</p>';
			}
			$text .= '<div class="navbar-right">';
				$text .= '<p class="navbar-text">Logged ind som '.$_SESSION['user']['name'].'</p>';
			$text .= '</div>';
		$text .= '</div>';
	$text .= '</nav>';
	return $text;
}

?>