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
		$text .= '</div>';
	$text .= '</nav>';
	return $text;
}

?>