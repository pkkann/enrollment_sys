<?php

function load_guests() {
	$objResponse = new xajaxResponse();
	$text .= gen_guests();
	$objResponse->call('xajax_load_nav', "2");
	$objResponse->assign("middle_wrapper", "innerHTML", $text);
	return $objResponse;
}

function gen_guests() {
	$text = "Guests";

	return $text;
}

?>