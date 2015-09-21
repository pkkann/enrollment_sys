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
		$text .= '<nav id="top_nav" class="navbar navbar-inverse navbar-fixed-top animated fadeInDown">';
			$text .= gen_nav();
		$text .= '</nav>';
	$text .= '</div>';

	$text .= '<div id="middle_wrapper" class="animated fadeIn">';
		switch ($_SESSION['curPage']) {
			case '1':
				$text .= gen_residents();
				break;
			case '2':
				$text .= gen_guests();
				break;
			case '3':
				if($_SESSION['user']['admin']) {
					$text .= gen_users();
				} else {
					$_SESSION['curPage'] = 1;
					$text .= gen_main();
				}
				break;
			case '4':
				$text .= gen_shifts();
				break;
		}
	$text .= '</div>';

	$text .= '<div id="bottom_wrapper">';
		$text .= '<nav id="bottom_nav" class="navbar navbar-default navbar-fixed-bottom animated fadeInUp">';
			$text .= gen_footer();
		$text .= '</nav>';
	$text .= '</div>';

	return $text;
}	
	
?>