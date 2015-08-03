<?php
	
function load_main() {
	$objResponse = new xajaxResponse();
	$text .= gen_main();
	$objResponse->assign("site_wrapper", "innerHTML", $text);
	return $objResponse;
}

function gen_main() {
	$text = '';

	$text .= '<div id="top_wrapper">';
		$text .= gen_nav();
	$text .= '</div>';

	$text .= '<div id="middle_wrapper">';
		switch ($_SESSION['curPage']) {
			case '1':
				$text .= gen_residents();
				break;
			case '2':
				$text .= gen_guests();
				break;
		}
	$text .= '</div>';

	$text .= '<div id="bottom_wrapper">';
		$text .= gen_footer();
	$text .= '</div>';

	return $text;
}	
	
?>